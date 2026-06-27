<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\SosService;
use Illuminate\Support\Facades\Log;

class SmsWebhookController extends Controller
{
    protected $sosService;

    public function __construct(SosService $sosService)
    {
        $this->sosService = $sosService;
    }

    /**
     * Handle incoming SMS from Gateway (e.g., Twilio / Local SMS Gateway)
     */
    public function handleIncomingSms(Request $request)
    {
        // Extract phone and message body
        // Supports Twilio-like 'From' & 'Body' or generic 'phone' & 'text'
        $phone = $request->input('From', $request->input('phone'));
        $body = $request->input('Body', $request->input('text'));

        Log::info('Incoming SMS SOS received', ['phone' => $phone, 'body' => $body]);

        if (!$body || stripos($body, 'SOS') !== 0) {
            return response()->json(['status' => 'ignored', 'message' => 'Not an SOS command']);
        }

        // Find user by phone (fallback to first user if not found for testing purposes)
        $user = null;
        if ($phone) {
            // Clean phone string to avoid +62 vs 08 format issues
            $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
            if (strlen($cleanPhone) > 8) {
                $user = User::where('phone', 'like', '%' . substr($cleanPhone, -8) . '%')->first();
            }
        }
        
        if (!$user) {
            $user = User::first(); // Fallback to an existing user to satisfy foreign key constraints
        }

        // Parse body: "SOS [Jumlah_Orang] [Deskripsi]"
        // Example: "SOS 5 Kami terjebak di atap rumah karena air naik"
        $parts = explode(' ', trim($body), 3);
        
        $peopleTrapped = 1;
        $desc = "Sinyal darurat dikirimkan secara offline melalui SMS.";

        if (count($parts) >= 2) {
            $parsedPeople = (int) $parts[1];
            if ($parsedPeople > 0) {
                $peopleTrapped = $parsedPeople;
            } else {
                // if the second word is not a number, consider it part of the description
                $desc = $parts[1] . (isset($parts[2]) ? ' ' . $parts[2] : '');
            }

            if (isset($parts[2]) && $parsedPeople > 0) {
                $desc = $parts[2];
            }
        }

        $data = [
            'user_id' => $user->user_id ?? 1,
            'people_trapped' => $peopleTrapped,
            'vulnerable_groups_count' => 0, // Cannot determine from simple SMS, default 0
            'description' => '[OFFLINE SMS] ' . $desc,
            'latitude' => -6.241586, // Default Bekasi center
            'longitude' => 106.992416, // Default Bekasi center
        ];

        try {
            $this->sosService->createSos($data);
            Log::info('Offline SMS SOS successfully created', ['user_id' => $user->user_id]);
            
            // Standard XML Response for Twilio (TwiML) or generic JSON
            if ($request->has('From')) {
                return response('<Response><Message>SOS Darurat Diterima. Tim penyelamat telah disiagakan.</Message></Response>')
                    ->header('Content-Type', 'text/xml');
            }
            
            return response()->json(['status' => 'success', 'message' => 'SOS dispatched']);
        } catch (\Exception $e) {
            Log::error('Failed to create SMS SOS', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}

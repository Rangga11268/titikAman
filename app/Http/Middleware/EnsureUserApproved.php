<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $status = auth()->user()->status;
            // Null status treated as approved (DB default)
            if ($status !== null && $status !== 'approved') {
                return redirect()->route('verification.status');
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Jobs;

use App\Services\ImageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CompressDonationImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $imagePath;

    /**
     * Create a new job instance.
     *
     * @param string $imagePath Relative path inside public storage
     */
    public function __construct(string $imagePath)
    {
        $this->imagePath = $imagePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $absolutePath = storage_path('app/public/' . $this->imagePath);
        if (file_exists($absolutePath)) {
            ImageService::compressFileInPlace($absolutePath, 50); // Compress to 50% quality
        }
    }
}

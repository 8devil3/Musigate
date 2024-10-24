<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RemoveNotVerifiedUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Log::info('Remove not verified users start');
        
        $users = User::whereNull('email_verified_at')->whereNull('google_id')->whereDate('created_at', today()->subDays(3)->toDateString());
        
        \Log::info('Count not verified users: ' . $users->count());
        
        $users->delete();

        \Log::info('Remove not verified users end');
    }
}

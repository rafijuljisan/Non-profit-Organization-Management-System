<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Subscription;

class SuspendUnpaidMembers extends Command
{
    /**
     * The name and signature of the console command.
     * এই কমান্ডটি আমরা টার্মিনালে রান করব।
     */
    protected $signature = 'members:auto-suspend';

    /**
     * The console command description.
     */
    protected $description = 'Automatically suspend members who have 3 or more unpaid subscriptions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for unpaid members...');

        // Only get currently active members
        $activeMembers = User::where('status', 'active')->get();
        $suspendedCount = 0;

        foreach ($activeMembers as $member) {
            // Count how many unpaid subscriptions this specific member has
            $unpaidCount = Subscription::where('user_id', $member->id)
                                       ->where('status', 'unpaid')
                                       ->count();

            // If unpaid subscriptions are 3 or more, suspend the member
            if ($unpaidCount >= 3) {
                $member->update(['status' => 'suspended']);
                $suspendedCount++;
                
                $this->line("Suspended Member ID: {$member->member_id} (Unpaid: {$unpaidCount} months)");
            }
        }

        $this->info("Task Completed! Total {$suspendedCount} members have been suspended.");
    }
}
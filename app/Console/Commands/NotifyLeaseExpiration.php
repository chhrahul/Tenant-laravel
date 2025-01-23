<?php

namespace App\Console\Commands;
use App\Models\DataEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

use Illuminate\Console\Command;

class NotifyLeaseExpiration extends Command
{
    protected $signature = 'notify:lease-expiration';

    protected $description = 'Notify about lease expirations in 18 and 12 months';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = Carbon::now();

        $dates = [
            $today->copy()->addMonths(18)->format('Y-m-d'),
            $today->copy()->addMonths(12)->format('Y-m-d'),
        ];

        \Log::info('Checking leases with expiration dates:', $dates);

        $leases = DataEntry::whereIn('lease_expiration', $dates)->get();

        if ($leases->isEmpty()) {
            $this->info('No leases found for notification.');
            \Log::info('No leases found for notification.');
            return;
        }

        $emails = ['sanjit.softweaver@gmail.com'];

        $emailContent = "The following leases are expiring:\n\n";
        foreach ($leases as $lease) {
            $emailContent .= "Tenant: {$lease->tenant_name}, Lease Expiration: {$lease->lease_expiration}\n";
            \Log::info("Lease expiring: Tenant: {$lease->tenant_name}, Lease Expiration: {$lease->lease_expiration}");
        }

        foreach ($emails as $email) {
            Mail::raw($emailContent, function ($message) use ($email) {
                $message->to($email)
                        ->subject('Lease Expiration Notification');
            });
            \Log::info("Notification email sent to: {$email}");
        }

        $this->info('Notification emails sent to all admins.');
        \Log::info('Notification emails sent to all admins.');
    }
}

<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Jobs\ProcessExpiredCustomers;
use App\Models\Customer;


class ExpireCustomers extends Command
{
    protected $signature = 'customers:expire {--mikrotik=}';
    protected $description = 'Dispatch job untuk proses pelanggan kedaluwarsa';

    public function handle()
    {
        $mikrotikId = $this->option('mikrotik');
        $user = auth()->user(); // untuk web
        $now = now();

         // 🟢 1. Nonaktifkan pelanggan expired
        $expiredQuery = Customer::where('is_active', true)
            ->whereNotNull('expired_at')
            ->where('expired_at', '<=', $now)
            ->where('user_id', $user->id);

        if ($mikrotikId) {
            $expiredQuery->where('mikrotik_id', $mikrotikId);
        }
        $expiredCustomers = $expiredQuery->get();
        foreach ($expiredCustomers as $customer) {
            // dd($customer);
            ProcessExpiredCustomers::dispatch('expire', $customer);
        }

        $soonQuery = Customer::where('is_active', true)
            ->whereNotNull('expired_at')
            ->where('expired_at', '<=', now()->addDays(3))
            ->where('expired_at', '>', now())
            // ->whereNull('notified_expiring_at')
            ->where('user_id', $user->id);

        if ($mikrotikId) {
            $soonQuery->where('mikrotik_id', $mikrotikId);
        }

        $soonToExpire = $soonQuery->get();
        foreach ($soonToExpire as $customer) {
            ProcessExpiredCustomers::dispatch('expiring_soon', $customer);
        }
        // ProcessExpiredCustomers::dispatch($user, $mikrotikId);

        $this->info('Proses penanganan kedaluwarsa telah dimasukkan ke antrian.');
    }
}
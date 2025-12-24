<?php

namespace App\Http\Controllers\Tenant;

use App\Models\BillingCycle;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class DashboardController extends Controller
{
    public function index()
    {
        // Ambil konfigurasi, jika belum ada buat default
        $config = Configuration::first();
        $billingCycle = BillingCycle::where('user_id', auth()->id())->first();
        if (!$config) {
           return redirect()->route('tenant.configuration.create')->with('error', 'Konfigurasi belum dibuat.');
        }

        // Data dummy untuk statistik (nanti bisa diganti dari database)
        $totalCustomers = 1240;
        $activeCount = 980;
        $expiringSoon = 24;
        $expiredToday = 8;
        $onlineUsersCount = 321;
        // dd($config);

        return view('dashboard', compact(
            'config',
            'totalCustomers',
            'activeCount',
            'expiringSoon',
            'expiredToday',
            'onlineUsersCount',
            'billingCycle'
        ));
    }
}
<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EmailService;
use App\Models\NotificationLog;

class NotificationController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'mikrotik_id' => 'required|exists:mikrotiks,id',
            'type' => 'required|in:expiring_soon,expired'
        ]);

        $customer = Customer::findOrFail($request->customer_id);
        $mikrotik = Mikrotik::findOrFail($request->mikrotik_id);

        $emailService = new EmailService();

        if ($request->type === 'expiring_soon') {
            $subject = 'Pemberitahuan: Akun Anda Akan Kedaluwarsa';
            $view = 'emails.expiring_soon';
            $data = ['customer' => $customer];
        } else {
            $subject = 'Akun Anda Telah Kedaluwarsa';
            $view = 'emails.expired';
            $data = ['customer' => $customer];
        }

        $result = $emailService->sendEmailViaMikrotikSmtp(
            $mikrotik,
            $customer->email,
            $subject,
            $view,
            $data
        );

        // Simpan log
        NotificationLog::create([
            'customer_id' => $customer->id,
            'mikrotik_id' => $mikrotik->id,
            'type' => $request->type,
            'subject' => $subject,
            'message' => $result['success'] ? 'Email terkirim' : $result['error'],
            'success' => $result['success'],
            'error' => $result['error'],
            'sent_at' => now(),
        ]);

        if ($result['success']) {
            return response()->json(['message' => 'Email berhasil dikirim.']);
        }

        return response()->json(['message' => 'Gagal kirim: ' . $result['error']], 500);
    }
}

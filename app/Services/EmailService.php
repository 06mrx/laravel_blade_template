<?php
namespace App\Services;

use App\Models\Mikrotik;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class EmailService
{
    public function sendEmailViaMikrotikSmtp(
        Mikrotik $mikrotik,
        $to,
        $subject,
        $view,
        $data = []
    ) {
        if (!$mikrotik->gmail || !$mikrotik->app_password_encrypted) {
            return [
                'success' => false,
                'error' => 'Konfigurasi Gmail & App Password belum diatur.'
            ];
        }

        try {
            $config = [
                'driver' => 'smtp',
                'host' => 'smtp.gmail.com',
                'port' => 587,
                'encryption' => 'tls',
                'username' => $mikrotik->gmail,
                'password' => Crypt::decrypt($mikrotik->app_password_encrypted),
                'from' => [
                    'address' => $mikrotik->gmail,
                    'name' => "Notifikasi " . $mikrotik->name
                ],
            ];

            // Simpan sementara konfigurasi mail
            config(['mail' => $config]);

            Mail::send($view, $data, function ($message) use ($to, $subject, $config) {
                $message->to($to)
                        ->subject($subject)
                        ->from($config['from']['address'], $config['from']['name']);
            });

            return ['success' => true, 'error' => null];
        } catch (\Exception $e) {
            Log::error("Gagal kirim email via MikroTik {$mikrotik->id}: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
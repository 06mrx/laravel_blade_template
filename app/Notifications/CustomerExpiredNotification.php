<?php

namespace App\Notifications;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerExpiredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Customer $customer)
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Akun Anda Telah Kedaluwarsa')
            ->greeting("Halo, {$this->customer->name}")
            ->line('Akun internet Anda telah kedaluwarsa.')
            ->line('Username: ' . $this->customer->username)
            ->line('Silakan hubungi penyedia layanan untuk perpanjangan.')
            ->action('Perpanjang Sekarang', url('/login'))
            ->line('Terima kasih telah menggunakan layanan kami.');
    }
}
<?php

namespace App\Notifications;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerExpiringSoonNotification extends Notification implements ShouldQueue
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
        $expireDate = $this->customer->expired_at->format('d M Y');

        return (new MailMessage)
            ->subject('Pemberitahuan: Akun Anda Akan Kedaluwarsa')
            ->greeting("Halo, {$this->customer->name}")
            ->line('Kami ingin mengingatkan bahwa akun internet Anda akan segera kedaluwarsa.')
            ->line("**Username**: {$this->customer->username}")
            ->line("**Tanggal Kedaluwarsa**: {$expireDate}")
            ->line('Silakan segera perpanjang layanan Anda untuk terus menikmati koneksi.')
            ->action('Perpanjang Sekarang', url('/login'))
            ->line('Terima kasih atas kepercayaan Anda.')
            ->salutation('Salam, Tim Layanan');
    }
}
<?php

namespace App\Jobs;

use App\Models\BillingCycle;
use App\Models\Configuration;
use App\Models\Customer;
use App\Models\NotificationLog;
use App\Services\MikrotikApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Models\Invoice;

class ProcessExpiredCustomers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $type;
    public $customer;

    public $timeout = 300;
    public $tries = 3;

    public function __construct($type = null, $customer)
    {
        $this->type = $type;
        $this->customer = $customer;
    }

    public function handle()
    {

        $now = now();

        if ($this->type == 'expire') {
            $this->syncToRadius();
            
            $this->handleExpiredCustomer($this->customer);
        }


        // 🟡 2. Kirim notifikasi 3 hari sebelum expired

        if ($this->type == 'expiring_soon') {
            $this->createInvoiceIfNotExists($this->customer);
            $this->handleExpiringSoonCustomer($this->customer);
        }
    }

    private function handleExpiredCustomer($customer)
    {

        try {
            $api = new MikrotikApiService(
                $customer->mikrotik->ip_address,
                $customer->mikrotik->username,
                decrypt($customer->mikrotik->password),
                $customer->mikrotik->port
            );

            // 🔧 Pastikan semua settingan isolir ada
            // $api->ensureIsolirSetup();

            // Ubah profile di MikroTik ke `isolir` via radius Mikrotik-Group
            //4. radreply: Mikrotik-Profile
            $radiusDB = DB::connection('mysql_radius');
            if ($customer->package) {
                $radiusDB->table('radreply')->updateOrInsert(
                    ['username' => $customer->username, 'attribute' => 'Mikrotik-Group'],
                    [
                        'op' => ':=',
                        'value' => 'isolir',
                    ]
                );
                $radiusDB->table('radreply')->updateOrInsert(
                    ['username' => $customer->username, 'attribute' => 'Framed-Pool'],
                    [
                        'op' => ':=',
                        'value' => 'pool-isolir',
                    ]
                );
            }

            $this->kickCustomerFromMikrotik($customer);

            $customer->update(['status' => 'isolir']);
            $this->createInvoiceIfNotExists($customer);

            if ($customer->email && $customer->mikrotik) {
                $this->sendExpiredNotification($customer);
            }
            $customer->update(['notified_expiring_at' => now()]);

        } catch (\Exception $e) {
            \Log::warning("Gagal isolir {$customer->username}: " . $e->getMessage());
             $this->logNotification($customer, "expired", false, $e->getMessage());
        } finally {

            // Cek apakah perlu buat invoice
            // if ($customer->next_invoice_date && $customer->next_invoice_date->lte(now())) {
            if (!$customer->hasUnpaidInvoices()) {
                // Buat invoice
                $this->createInvoiceIfNotExists($customer);
                //  $this->logNotification($customer, "expired", false, 'Finally');

                // Hitung berikutnya
                // $billingCycle = $customer->billingCycle ?? BillingCycle::findDefaultFor($customer->mikrotik_id);
                // $next = $billingCycle->getNextDueDate($customer->registration_date);

                // $customer->update(['next_invoice_date' => $next]);
            }

        }

        // $this->updatePppSecretToIsolir($customer);


    }

    private function createInvoiceIfNotExists($customer)
    {
        $issueDate = now();
        
        $dueDate = now()->startOfMonth()->addDays(5); // jatuh tempo 5 hari setelah tagihan
        $billingCycle = BillingCycle::where('user_id', $customer->created_by)->first();
        $next = $billingCycle->getNextDueDate($issueDate);
        $exists = Invoice::where('customer_id', $customer->id)
            ->whereMonth('issue_date', $issueDate->month)
            ->whereYear('issue_date', $issueDate->year)
            ->exists();

        if (!$exists && $customer->package) {
            // $billingCycle = BillingCycle
            Invoice::create([
                'customer_id' => $customer->id,
                'package_id' => $customer->package->id,
                'amount' => $customer->package->price ?? 0,
                'issue_date' => $issueDate,
                'due_date' => $customer->expired_at,
                'status' => 'unpaid',
                'notes' => 'Tagihan perpanjangan paket.',
            ]);
        }
    }
    private function updatePppSecretToIsolir($customer)
    {
        try {
            $api = new MikrotikApiService(
                $customer->mikrotik->ip_address,
                $customer->mikrotik->username,
                decrypt($customer->mikrotik->password),
                $customer->mikrotik->port
            );

            if (!$customer->mikrotik || !$api->pppSecretExists($customer->username)) {
                return;
            }

            $api->updatePppSecretProfile($customer->username, 'isolir');
        } catch (\Exception $e) {
            \Log::warning("Gagal ubah profile ke isolir untuk {$customer->username}: " . $e->getMessage());
        }
    }

    private function handleExpiringSoonCustomer($customer)
    {
        if ($customer->email && $customer->mikrotik) {
            $this->sendExpiringSoonNotification($customer);
        }

        $customer->update(['notified_expiring_at' => now()]);
    }

    private function sendExpiredNotification($customer)
    {
        
        
        $this->sendEmail($customer, 'Akun Anda Telah Kedaluwarsa', 'emails.expired', 'expired');
    }

    private function sendExpiringSoonNotification($customer)
    {
        $this->sendEmail($customer, 'Pemberitahuan: Akun Anda Akan Kedaluwarsa', 'emails.expiring_soon', 'expiring_soon');
    }

    private function sendEmail($customer, $subject, $view, $type)
    {
        $mikrotik = $customer->mikrotik;

        if (!$mikrotik->gmail || !$mikrotik->app_password_encrypted) {
            $this->logNotification($customer, $type, false, 'Konfigurasi Gmail tidak lengkap');
            return;
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
                    'name' => "Notifikasi {$mikrotik->name}"
                ],
            ];

            config(['mail' => $config]);

            $invoice = Invoice::where('customer_id', $customer->id)->whereIn('status', ['unpaid', 'overdue'])->first();
            $configuration = Configuration::where('created_by', $customer->created_by)->first();
            Mail::send($view, ['customer' => $customer, 'invoice' => $invoice, 'configuration' => $configuration], function ($message) use ($customer, $subject, $config) {
                $message->to($customer->email)
                    ->subject($subject)
                    ->from($config['from']['address'], $config['from']['name']);
            });

            $this->logNotification($customer, $type, true);
        } catch (\Exception $e) {
            $this->logNotification($customer, $type, false, $e->getMessage());
        }
    }

    private function logNotification($customer, $type, $success, $error = null)
    {
        NotificationLog::create([
            'customer_id' => $customer->id,
            'mikrotik_id' => $customer->mikrotik?->id,
            'type' => $type,
            'subject' => $type === 'expired' ? 'Akun Anda Telah Kedaluwarsa' : 'Pemberitahuan: Akun Anda Akan Kedaluwarsa',
            'message' => $success ? 'Email terkirim' : 'Gagal dikirim',
            'success' => $success,
            'error' => $error,
            'sent_at' => now(),
        ]);
    }

    private function kickCustomerFromMikrotik($customer)
    {
        if (!$customer->mikrotik)
            return;

        try {
            $api = new MikrotikApiService(
                $customer->mikrotik->ip_address,
                $customer->mikrotik->username,
                decrypt($customer->mikrotik->password),
                $customer->mikrotik->port
            );

            $api->kickPppActive($customer->username);
        } catch (\Exception $e) {
            \Log::warning("Gagal kick {$customer->username}: " . $e->getMessage());
        }
    }
    private function syncToRadius()
    {
        $radiusDB = DB::connection('mysql_radius');
        $inactiveUsernames = Customer::where('status', 'isolir')->where('mikrotik_id', $this->customer->mikrotik_id)
            ->where('user_id', $this->customer->user->id)
            // ->when($this->customer->mikrotik_id, fn($q) => $q->where('mikrotik_id', $this->mikrotikId))
            ->pluck('username');

        // if ($inactiveUsernames->isNotEmpty()) {
        //     foreach ($inactiveUsernames as $username) {
        //         // Set upload/download rate ke 20kbps (20k = 20000bps)
        //         $radiusDB->table('radreply')->updateOrInsert(
        //             ['username' => $username, 'attribute' => 'Mikrotik-Rate-Limit'],
        //             ['op' => '=', 'value' => '30k/30k']
        //         );
        //     }
        // }
    }

}
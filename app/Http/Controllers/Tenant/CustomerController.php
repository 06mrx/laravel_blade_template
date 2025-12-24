<?php

namespace App\Http\Controllers\Tenant;

use App\Models\BillingCycle;
use Illuminate\Routing\Controller;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Invoice;
use App\Models\IpPool;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Services\MikrotikApiService;
use App\Models\Mikrotik;
// use Illuminate\Validation\Rule;


class CustomerController extends Controller
{
    public function __construct()
    {
        // $this->middleware('role:mikrotik_admin');
    }

    public function index(Request $request)
    {
        $query = Customer::where('user_id', auth()->id());
        // $odps = 
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }
        $status = request('status'); // 'aktif', 'isolir', 'terdaftar'

        if ($status) {
            $query->where('status', $status);
        }

        $customers = $query->with('package', 'ipPool')->paginate(25);

        return view('tenant.customer.index', compact('customers'));
    }

    public function create(Request $request)
    {
        $fromMikrotik = $request->query('from_mikrotik');
        $packages = Package::where('user_id', auth()->id())->where('mikrotik_id', $fromMikrotik)->get();
        $ipPools = IpPool::where('user_id', auth()->id())->where('mikrotik_id', $fromMikrotik)->get();
        $search = $request->query('search');
        $per_page = $request->query('per_page', 10);
        return view('tenant.customer.create', compact('packages', 'ipPools', 'fromMikrotik', 'search', 'per_page'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'id_number' => [
                'required',
                'string',
                'size:16',
                Rule::unique('customers', 'id_number')
                    ->where('mikrotik_id', $request->mikrotik_id)
                    ->whereNull('deleted_at')
            ],
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'package_id' => 'nullable|exists:packages,id',
            'ip_pool_id' => 'nullable|exists:ip_pools,id',
            'expired_at' => 'nullable|date',
            'mikrotik_id' => 'required|exists:mikrotiks,id',
        ]);

        $mikrotik = Mikrotik::findOrFail($request->mikrotik_id);

        // Format IP Mikrotik: hapus titik
        // $ipPrefix = str_replace('.', '', $mikrotik->ip_address); // 192.168.1.1 → 19216811
        $uuidPrefix = substr(Str::uuid()->toString(), 0, 8);
        // Generate username: {ip_prefix}_{id_number}
        // $username = $ipPrefix . '_' . $request->id_number;
        $username = $uuidPrefix . $request->id_number;
        // Password = username
        $password = $username;


        // Saat buat pelanggan baru
        // $billingCycle = $request->billing_cycle_id
        //     ? BillingCycle::find($request->billing_cycle_id)
        //     : BillingCycle::defaultForMikrotik($mikrotik->id)->first();
        $billingCycle = BillingCycle::where('mikrotik_id', $mikrotik->id)->first() ?? BillingCycle::where('created_by', auth()->id())->where('is_default', true)->first();
        $registrationDate = now();
        $nextInvoiceDate = $billingCycle->getNextDueDate($registrationDate);
        // dd($nextInvoiceDate);
        $customer = Customer::create([
            'name' => $request->name,
            'billing_cycle_id' => $billingCycle->id,
            'registration_date' => $registrationDate,
            // 'next_invoice_date' => $nextInvoiceDate,
            'id_number' => $request->id_number,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'username' => $username,
            'password' => ($password),
            'package_id' => $request->package_id,
            'ip_pool_id' => $request->ip_pool_id,
            // 'expired_at' => $nextInvoiceDate,
            'status' => 'terdaftar', // Default status
            'mikrotik_id' => $mikrotik->id,
            'user_id' => auth()->id(),
        ]);

        
        // Sinkron ke RADIUS DB
        $this->syncToRadius($customer);

        if ($request->filled('from_mikrotik')) {
            return redirect()
                ->route('tenant.mikrotik.show', $request->from_mikrotik)
                ->with('success', 'Pelanggan berhasil dibuat dan disinkronkan ke RADIUS.');
        }

        return redirect()->route('tenant.customer.index')
            ->with('success', 'Pelanggan berhasil dibuat.');
    }

    public function edit(Customer $customer, Request $request)
    {
        $fromMikrotik = $request->query('from_mikrotik');
        $this->authorizeCustomer($customer);
        $packages = Package::where('user_id', auth()->id())->where('mikrotik_id', $customer->mikrotik_id)->get();
        $ipPools = IpPool::where('user_id', auth()->id())->where('mikrotik_id', $customer->mikrotik_id)->get();
        $search = $request->query('search');
        $per_page = $request->query('per_page', 10);
        return view('tenant.customer.edit', compact('customer', 'packages', 'ipPools', 'fromMikrotik', 'search', 'per_page'));
    }

    public function show(Customer $customer) {
        $this->authorizeCustomer($customer);
        $invoices = $customer->invoices()->paginate(12);
        return view('tenant.customer.show', compact('customer', 'invoices'));
    }

    public function update(Request $request, Customer $customer)
    {
        $this->authorizeCustomer($customer);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'id_number' => [
                'required',
                'string',
                'size:16',
                Rule::unique('customers', 'id_number')
                    ->where('mikrotik_id', $request->mikrotik_id)
                    ->whereNull('deleted_at')
            ],
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            // 'username' => 'required|string|unique:customers,username,' . $customer->id,
            // 'password' => 'nullable|string|min:6',
            'package_id' => 'nullable|exists:packages,id',
            'ip_pool_id' => 'nullable|exists:ip_pools,id',
            'expired_at' => 'nullable|date',
            'status' => 'in:terdaftar,aktif,isolir',
            'notified_expiring_at' => 'nullable|date', // Tambahkan validasi jika perlu
        ]);

        // 'notified_expiring_at' => null, // reset
        $oldStatus = $customer->status;
        $data = $request->only(['name', 'email', 'phone', 'address', 'id_number', 'package_id', 'ip_pool_id', 'expired_at', 'status', 'notified_expiring_at']);
        $mikrotik = Mikrotik::findOrFail($customer->mikrotik_id);

        // Format IP Mikrotik: hapus titik
        $ipPrefix = str_replace('.', '', $mikrotik->ip_address); // 192.168.1.1 → 19216811

        // Generate username: {ip_prefix}_{id_number}
        // $username = $ipPrefix . '_' . $request->id_number;
        // $data['username'] = $username;
        // $data['password'] = $username; // Password = username


        $customer->update($data);

        // Jika status berubah dari isolir ke aktif, kembalikan profile
        if ($oldStatus === 'isolir' && $customer->status === 'aktif') {
            $this->restorePppSecretFromIsolir($customer);
        }

        // Sinkron ulang ke RADIUS
        $this->syncToRadius($customer);

        if ($request->filled('from_mikrotik')) {
            return redirect()
                ->route('tenant.mikrotik.show', $request->from_mikrotik)
                ->with('success', 'Pelanggan berhasil diperbarui dan disinkronkan ke RADIUS.');
        }

        return redirect()->route('tenant.customer.index')
            ->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function toggleStatus(Request $request) {
        $customer = Customer::findOrFail($request->customer);
        // dd($request->all());
        $this->authorizeCustomer($customer);
        $customer = Customer::findOrFail($customer->id);
        if($request->old_status == 'terdaftar') {
            $customer->installation_date = now();
            $billingCycle = BillingCycle::where('mikrotik_id', $customer->mikrotik->id)->first() ?? BillingCycle::where('created_by', auth()->id())->where('is_default', true)->first();
            $registrationDate = now();
            $nextInvoiceDate = $billingCycle->getNextDueDate($registrationDate);
            $customer->next_invoice_date = $nextInvoiceDate;
            $customer->expired_at = $nextInvoiceDate;
            $this->createInvoiceIfNotExists($customer);
            // $invoice = $customer->invoices()->where('status', 'unpaid')->first();
            
        }
        $customer->status = $request->status;
        $customer->save();
         return redirect()
                ->route('tenant.mikrotik.show', $customer->mikrotik_id)
                ->with('success', 'Pelanggan berhasil diperbarui.');
    }
    private function restorePppSecretFromIsolir($customer, $oldPackageId = null)
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

            // Gunakan package saat ini, atau kembali ke yang lama
            $packageId = $customer->package_id ?? $oldPackageId;
            $packageName = $packageId ? Package::find($packageId)->name : 'default';

            $api->updatePppSecretProfile($customer->username, $packageName);
        } catch (\Exception $e) {
            \Log::warning("Gagal kembalikan profile dari isolir: " . $e->getMessage());
        }
    }

    public function destroy(Customer $customer, Request $request)
    {
        $this->authorizeCustomer($customer);
        $customer->delete();

        // Hapus dari RADIUS
        $this->deleteFromRadius($customer);

        if ($request->filled('from_mikrotik')) {
            return redirect()
                ->route('tenant.mikrotik.show', $request->from_mikrotik)
                ->with('success', 'Pelanggan berhasil dihapus dari sistem dan RADIUS.');
        }

        $search = $request->query('search');
        $per_page = $request->query('per_page', 10);
        $mikrotik = $customer->mikrotik_id;
        return redirect()->route('tenant.mikrotik.show', compact('search', 'per_page', 'mikrotik'))
            ->with('success', 'Pelanggan berhasil dihapus.');
    }

    private function authorizeCustomer(Customer $customer)
    {
        if ($customer->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }

    // ─────────────────────────────────────────────────────
    // 🔁 Sinkronisasi ke RADIUS DB
    // ─────────────────────────────────────────────────────
    private function syncToRadius(Customer $customer)
    {
        $radiusDB = \DB::connection('mysql_radius');

        // 1. radcheck: username & password
        $radiusDB->table('radcheck')->updateOrInsert(
            ['username' => $customer->username],
            [
                'attribute' => 'Cleartext-Password',
                'op' => ':=',
                'value' => $customer->password,
            ]
        );

        // 2. radreply: bandwidth limit
        if ($customer->package) {
            $radiusDB->table('radreply')->updateOrInsert(
                ['username' => $customer->username, 'attribute' => 'Mikrotik-Rate-Limit'],
                [
                    'op' => ':=',
                    'value' => "{$customer->package->speed_down}/{$customer->package->speed_up}",
                ]
            );

            // Session timeout jika ada durasi
            if ($customer->package->duration_days) {
                $radiusDB->table('radreply')->updateOrInsert(
                    ['username' => $customer->username, 'attribute' => 'Session-Timeout'],
                    [
                        'op' => ':=',
                        'value' => $customer->package->duration_days * 24 * 3600,
                    ]
                );
            }
        }

        // 3. radreply: Framed-Pool (IP Pool)
        if ($customer->ipPool) {
            $radiusDB->table('radreply')->updateOrInsert(
                ['username' => $customer->username, 'attribute' => 'Framed-Pool'],
                [
                    'op' => ':=',
                    'value' => $customer->ipPool->name,
                ]
            );
        }

        // //4. radreply: Mikrotik-Profile
        // if ($customer->package) {
        //     $radiusDB->table('radreply')->updateOrInsert(
        //         ['username' => $customer->username, 'attribute' => 'Mikrotik-Group'],
        //         [
        //             'op' => ':=',
        //             'value' => 'default', // Ganti dengan nama profile default jika ada
        //         ]
        //     );
        // }

        //4. Hapus attribute Mikrotik-Group jika ada
        $radiusDB->table('radreply')->where('username', $customer->username)
            ->where('attribute', 'Mikrotik-Group')
            ->delete();
    }

    private function deleteFromRadius(Customer $customer)
    {
        $radiusDB = \DB::connection('mysql_radius');

        $radiusDB->table('radcheck')->where('username', $customer->username)->delete();
        $radiusDB->table('radreply')->where('username', $customer->username)->delete();
    }

    public function createInvoiceIfNotExists($customer)
    {
        $issueDate = now()->startOfMonth();
        $dueDate = now()->startOfMonth()->addDays(5); // jatuh tempo 5 hari setelah tagihan
        $billingCycle = BillingCycle::where('user_id', auth()->id())->first();
        $amount = $customer->package->price;
        
        $daysLeft = now()->diff($customer->expired_at)->days;
        // dd($daysLeft);
        if ($billingCycle->type == 'fixed') {
            $amount = ($amount / 30) * $daysLeft;
        }
        $exists = Invoice::where('customer_id', $customer->id)
            ->whereMonth('issue_date', $issueDate->month)
            ->whereYear('issue_date', $issueDate->year)
            ->exists();

        if (!$exists && $customer->package) {
            Invoice::create([
                'customer_id' => $customer->id,
                'package_id' => $customer->package->id,
                'amount' => $amount ?? 0,
                'issue_date' => $issueDate,
                'due_date' => $customer->expired_at,
                'status' => 'unpaid',
                'notes' => 'Tagihan perpanjangan paket.',
            ]);
        }
    }
}
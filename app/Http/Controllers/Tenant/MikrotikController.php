<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Routing\Controller;
use App\Models\Mikrotik;
use App\Models\IpPool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use App\Services\MikrotikApiService;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Odc;
use Illuminate\Support\Carbon;
use App\Models\NotificationLog;
use App\Jobs\ProcessExpiredCustomers;
// use Illuminate\Support\Facades\Crypt;

class MikrotikController extends Controller
{
    public function __construct()
    {
        // $this->middleware('role:tenant');
        $this->middleware('permission:view-mikrotik')->only(['index']);
        $this->middleware('permission:create-mikrotik')->only(['create', 'store']);
        $this->middleware('permission:edit-mikrotik')->only(['edit', 'update']);
        $this->middleware('permission:delete-mikrotik')->only(['destroy']);


    }

    public function index()
    {
        $mikrotiks = Mikrotik::where('created_by', auth()->id())->paginate(10);
        return view('tenant.mikrotik.index', compact('mikrotiks'));
    }

    public function create()
    {
        return view('tenant.mikrotik.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'ip_address' => 'required|ip',
            'port' => 'nullable|integer|min:1|max:65535',
            'username' => 'required|string',
            'password' => 'required|string',
            'description' => 'nullable|string',
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'gmail' => 'nullable|email',
            'app_password' => 'nullable|string', // 16-digit app password
        ]);

        Mikrotik::create([
            'name' => $request->name,
            'ip_address' => $request->ip_address,
            'port' => $request->port ?? 8728,
            'username' => $request->username,
            'password' => Crypt::encrypt($request->password),
            'description' => $request->description,
            'status' => $request->status,
            'gmail' => $request->gmail,
            'app_password_encrypted' => $request->app_password ? Crypt::encrypt($request->app_password) : null,
        ]);

        return redirect()->route('tenant.mikrotik.index')
            ->with('success', 'MikroTik berhasil ditambahkan. Notifikasi email siap jika diaktifkan.');
    }

    // public function show(Mikrotik $mikrotik)
    // {
    //     $this->authorizeMikrotik($mikrotik);
    //     return view('tenant.mikrotik.show', compact('mikrotik'));
    // }

    public function show(Mikrotik $mikrotik)
    {

        $this->authorizeMikrotik($mikrotik);
        $user = $mikrotik->user;

        // 🔐 Cek & Ganti Port API jika masih 8728
        if ($mikrotik->port == 8728) {
            try {
                $api = new MikrotikApiService(
                    $mikrotik->ip_address,
                    $mikrotik->username,
                    decrypt($mikrotik->password),
                    $mikrotik->port // masih 8728, tapi kita coba connect dulu
                );

                // Generate port acak di range 65000-65535
                $newPort = random_int(65000, 65530);

                // Coba ganti port via API
                $api->changeApiPort($newPort);

                // Jika berhasil, update di database
                $mikrotik->update(['port' => $newPort]);

                // Tambah session flash
                request()->session()->flash('success', "🔐 Port API telah diubah ke {$newPort} untuk keamanan.");
            } catch (\Exception $e) {
                // Jika gagal (misal: koneksi timeout), biarkan
                \Log::warning("Gagal ganti port API untuk {$mikrotik->name}: " . $e->getMessage());
            }
        }

        // Ambil parameter
        $search = request('search');
        $perPage = request('per_page', 10);
        $packageId = request('package_id');
        $status = request('status');
        $odp = request('odp_id');

        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 10;
        $odc = Odc::where('mikrotik_id', $mikrotik->id)->with('odps')->first() ?? [];
        // dd($odc->odps);
        // Query customers
        $customerQuery = $user->customers()
            ->where('mikrotik_id', $mikrotik->id)
            ->with('package', 'ipPool');

        if ($search) {
            $customerQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }


        if ($packageId) {
            $customerQuery->where('package_id', $packageId);
        }

        if ($odp) {
            $customerQuery->where('odp_id', $odp);
        }

        // if ($status === 'active') {
        //     $customerQuery->where('is_active', true);
        // } elseif ($status === 'inactive') {
        //     $customerQuery->where('is_active', false);
        // }

        if ($status) {
            $customerQuery->where('status', $status);
        }

        $customers = $customerQuery->paginate(25)->withQueryString();
        $packages = $user->packages()->orderBy('name')->get();

        // 🟢 Statistik Pelanggan (tetap dari Laravel)
        $activeCount = $user->customers()->where('status', 'aktif')->where('mikrotik_id', $mikrotik->id)->count();
        $expiredToday = $user->customers()
            ->where('status', 'isolir')
            ->where('mikrotik_id', $mikrotik->id)
            ->whereDate('expired_at', '<', today())
            ->count();
        // dd($expiredToday);
        $expiringSoon = $user->customers()
            ->where('status', 'aktif')
            ->where('mikrotik_id', $mikrotik->id)
            ->whereNotNull('expired_at')
            ->where('expired_at', '<=', now()->addDays(3))
            ->where('expired_at', '>', now())
            ->count();
        $totalCustomers = $customers->where('mikrotik_id', $mikrotik->id)->count(); // atau $user->customers()->count()



        // 📦 Paket Terpopuler
        $topPackages = $user->packages()
            ->where('mikrotik_id', $mikrotik->id)
            ->withCount('customers')
            ->whereHas('customers', function ($query) use ($mikrotik) {
                $query->where('mikrotik_id', $mikrotik->id);
            })
            ->having('customers_count', '>', 0)
            ->orderBy('customers_count', 'desc')
            ->limit(5)
            ->get();

        // 📈 Pertumbuhan Pelanggan (30 hari)
        $growthData = $user->customers()
            ->where('created_at', '>=', now()->subDays(30))
            ->where('mikrotik_id', $mikrotik->id)
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        $labels = [];
        $data = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');
            $data[] = $growthData[$date] ?? 0;
        }



        // 🔥 GANTI: Ambil Online Users dari MikroTik API
        try {
            $api = new MikrotikApiService(
                $mikrotik->ip_address,
                $mikrotik->username,
                decrypt($mikrotik->password),
                $mikrotik->port
            );

            $pppActive = $api->getPppActive(); // ✅ Gunakan method
            $onlineUsers = collect($pppActive);
            $onlineUsersCount = $onlineUsers->count();
            $isIsolirPoolExist = $api->ipPoolExists('pool-isolir'); // Cek apakah pool isolir ada
            $isIsolirProfileExist = $api->profileExists('isolir');
            // if (!$isIsolirPoolExist || !$isIsolirProfileExist) {
            //     // Jika pool isolir tidak ada, buat pool baru

            //     $api->createIsolirIpPool();
            //     $api->createIsolirProfile($mikrotik);
            // }
            $api->ensureIsolirSetup($mikrotik);
        } catch (\Exception $e) {
            $onlineUsers = collect();
            $onlineUsersCount = 0;
            \Log::warning("API MikroTik tidak bisa diakses: " . $e->getMessage());
        }

        // Buat mapping: username => ip dari MikroTik
        $onlineIpMap = $onlineUsers->keyBy('name'); // 'name' = username di PPP Active
        // Enrich customers dengan IP
        $customers->getCollection()->transform(function ($customer) use ($onlineIpMap) {
            $username = $customer->username;
            if (isset($onlineIpMap[$username])) {
                $customer->active_ip = $onlineIpMap[$username]['address'] ?? null;
                $customer->uptime = $onlineIpMap[$username]['uptime'] ?? null;
                $customer->is_online = true;
            } else {
                $customer->active_ip = null;
                $customer->uptime = null;
                $customer->is_online = false;
            }
            return $customer;
        });
        // dd($customers->first()->active_ip);
        // dd("ab");

        // ❌ HAPUS: Ambil dari RADIUS (kita ganti dengan API)
        // $radiusDB = \DB::connection('mysql_radius');
        // $recentSessions = $radiusDB->table('radacct')... -> kita ganti di bawah

        // 🕒 Recent Sessions: Tetap dari RADIUS (untuk riwayat)
        // Tapi jika ingin dari API, tidak tersedia — jadi tetap pakai RADIUS untuk riwayat

        // dd($mikrotik->username);

        // $radiusDB = \DB::connection('mysql_radius');
        // $recentSessions = $radiusDB->table('radacct')
        //     ->whereNotNull('acctstoptime')
        //     ->where('nasipaddress', $mikrotik->ip_address)
        //     ->where('acctstoptime', '>=', now()->subHours(24))
        //     ->orderBy('acctstoptime', 'desc')
        //     ->limit(50)
        //     ->get();
        // dd(1);

        // 🔔 Notification Logs
        $notificationLogs = NotificationLog::where('mikrotik_id', $mikrotik->id)
            ->with('customer')
            ->latest()
            ->paginate(10, ['*'], 'log_page');
        return view('tenant.mikrotik.show', compact(
            'mikrotik',
            'activeCount',
            'expiredToday',
            'expiringSoon',
            'totalCustomers',
            'topPackages',
            'labels',
            'data',
            'onlineUsers',
            'onlineUsersCount',
            // 'recentSessions',
            'customers',
            'packages',
            'notificationLogs',
            'odc'
        ));
    }

    public function edit(Mikrotik $mikrotik)
    {
        $this->authorizeMikrotik($mikrotik);
        return view('tenant.mikrotik.edit', compact('mikrotik'));
    }

    public function update(Request $request, Mikrotik $mikrotik)
    {
        $this->authorizeMikrotik($mikrotik);

        $request->validate([
            'name' => 'required|string|max:255',
            'ip_address' => 'required|ip',
            'port' => 'nullable|integer|min:1|max:65535',
            'username' => 'required|string',
            'password' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'gmail' => 'nullable|email',
            'app_password' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'ip_address', 'port', 'username', 'description', 'status', 'gmail']);

        if ($request->filled('password')) {
            $data['password'] = Crypt::encrypt($request->password);
        }

        if ($request->filled('app_password')) {
            $data['app_password_encrypted'] = Crypt::encrypt($request->app_password);
        } elseif ($request->has('app_password')) {
            $data['app_password_encrypted'] = null; // jika dikosongkan
        }

        $mikrotik->update($data);

        return redirect()->route('tenant.mikrotik.index')
            ->with('success', 'MikroTik berhasil diperbarui.');
    }

    public function destroy(Mikrotik $mikrotik)
    {
        $this->authorizeMikrotik($mikrotik);
        $mikrotik->delete();

        return redirect()->route('tenant.mikrotik.index')
            ->with('success', 'MikroTik berhasil dihapus.');
    }

    private function authorizeMikrotik(Mikrotik $mikrotik)
    {
        if ($mikrotik->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function syncCustomers(Request $request, Mikrotik $mikrotik)
    {
        $this->authorizeMikrotik($mikrotik);
        // dd($mikrotik->username);
        try {
            $api = new MikrotikApiService(
                $mikrotik->ip_address,
                $mikrotik->username,
                decrypt($mikrotik->password),
                $mikrotik->port
            );

            // dd($mikrotik->username);

            $pppSecrets = $api->getPPPSecrets();

            $syncedCount = 0;
            $updatedCount = 0;

            foreach ($pppSecrets as $secret) {
                // Hanya proses yang enabled
                if (isset($secret['disabled']) && $secret['disabled'] === 'true') {
                    continue;
                }

                $username = $secret['name'];
                $password = $secret['password'] ?? 'password123'; // fallback
                $service = $secret['service'] ?? 'pppoe';
                $remoteAddress = $secret['remote-address'] ?? null;
                $callerId = $secret['caller-id'] ?? null;
                $profile = $secret['profile'] ?? null;
                $comment = $secret['comment'] ?? null;

                // Cari atau buat pelanggan di Laravel
                $customer = $mikrotik->user->customers()->firstOrCreate(
                    ['username' => $username],
                    [
                        'name' => $comment ?: $username,
                        'email' => null,
                        'phone' => null,
                        'address' => $remoteAddress ? "Remote: {$remoteAddress}" : null,
                        'password' => $password,
                        'package_id' => $this->getPackageIdByName($mikrotik->created_by, $profile),
                        'ip_pool_id' => $this->getIpPoolIdByRemoteAddress($mikrotik->created_by, $remoteAddress),
                        'expired_at' => null, // tidak diatur di MikroTik
                        'status' => 'aktif',
                        'user_id' => $mikrotik->created_by,
                        'mikrotik_id' => $mikrotik->id,
                    ]
                );

                // Sinkron ke RADIUS
                $this->syncCustomerToRadius($customer);

                $syncedCount++;
            }

            return redirect()
                ->route('tenant.mikrotik.show', $mikrotik)
                ->with('success', "Sinkronisasi berhasil: {$syncedCount} pelanggan disinkronkan.");
        } catch (Exception $e) {
            return redirect()
                ->route('tenant.mikrotik.show', $mikrotik)
                ->with('error', 'Gagal sinkron: ' . $e->getMessage());
        }
    }

    // Helper: Dapatkan package_id berdasarkan nama profile di MikroTik
    private function getPackageIdByName($userId, $profileName)
    {
        if (!$profileName)
            return null;

        return \App\Models\Package::where('user_id', $userId)
            ->where('name', $profileName)
            ->value('id');
    }

    // Helper: Dapatkan ip_pool_id berdasarkan remote-address
    private function getIpPoolIdByRemoteAddress($userId, $remoteAddress)
    {
        if (!$remoteAddress)
            return null;

        $octets = explode('.', $remoteAddress);
        $network = $octets[0] . '.' . $octets[1] . '.' . $octets[2]; // 192.168.10

        return \App\Models\IpPool::where('user_id', $userId)
            ->where('range', 'like', "{$network}%")
            ->value('id');
    }

    // Sinkron ke RADIUS (dari controller sebelumnya)
    private function syncCustomerToRadius($customer)
    {
        $radiusDB = \DB::connection('mysql_radius');

        // radcheck
        $radiusDB->table('radcheck')->updateOrInsert(
            ['username' => $customer->username],
            [
                'attribute' => 'Cleartext-Password',
                'op' => ':=',
                'value' => $customer->password,
            ]
        );

        // radreply: bandwidth
        if ($customer->package) {
            $radiusDB->table('radreply')->updateOrInsert(
                ['username' => $customer->username, 'attribute' => 'Mikrotik-Rate-Limit'],
                [
                    'op' => ':=',
                    'value' => "{$customer->package->speed_down}/{$customer->package->speed_up}",
                ]
            );
        }

        // radreply: IP Pool
        if ($customer->ipPool) {
            $radiusDB->table('radreply')->updateOrInsert(
                ['username' => $customer->username, 'attribute' => 'Framed-Pool'],
                [
                    'op' => ':=',
                    'value' => $customer->ipPool->name,
                ]
            );
        }


    }

    public function syncIpPools(Request $request, Mikrotik $mikrotik)
    {
        $this->authorizeMikrotik($mikrotik);

        try {
            $api = new MikrotikApiService(
                $mikrotik->ip_address,
                $mikrotik->username,
                decrypt($mikrotik->password),
                $mikrotik->port
            );

            $mikrotikPools = $api->getIpPools();
            // dd($mikrotikPools);
            $syncedCount = 0;

            foreach ($mikrotikPools as $pool) {
                // dd($pool);
                $name = $pool['name'];
                $ranges = $pool['ranges']; // bisa multiple: "192.168.10.10-192.168.10.100"
                $nextPool = $pool['next-pool'] ?? null;

                $ipPool = $mikrotik->user->ipPools()->updateOrCreate(
                    ['name' => $name],
                    [
                        'range' => $ranges,
                        'next_pool' => $nextPool,
                        'mikrotik_id' => $mikrotik->id,
                        'description' => "Synced from MikroTik: {$mikrotik->name}",
                        'user_id' => $mikrotik->created_by,
                    ]
                );
                $syncedCount++;
            }

            return redirect()
                ->route('tenant.mikrotik.show', $mikrotik)
                ->with('success', "Sinkronisasi IP Pool berhasil: {$syncedCount} pool disinkronkan.");
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return redirect()
                ->route('tenant.mikrotik.show', $mikrotik)
                ->with('error', 'Gagal sinkron IP Pool: ' . $e->getMessage());
        }
    }

    /**
     * Sinkronisasi IP Pool ke RADIUS DB (radippool)
     */
    private function syncIpPoolToRadius($ipPool)
    {
        $radiusDB = \DB::connection('mysql_radius');

        // Pisahkan range jika ada multiple
        $ranges = explode(',', $ipPool->range);
        foreach ($ranges as $range) {
            $range = trim($range);
            if (empty($range))
                continue;

            [$start, $end] = $this->parseIpRange($range);

            if (!$start || !$end)
                continue;

            $radiusDB->table('radippool')->updateOrInsert(
                [
                    'pool_name' => $ipPool->name,
                    'framedipaddress' => $start,
                ],
                [
                    'nasipaddress' => '0.0.0.0', // bisa diisi jika perlu
                    'calledstationid' => '',
                    'callingstationid' => '',
                    'expiry_time' => null,
                    'username' => null,
                    'pool_key' => $ipPool->name,
                ]
            );
        }
    }

    /**
     * Parse range IP seperti "192.168.10.10-192.168.10.100"
     * Mengembalikan [start, end]
     */
    private function parseIpRange($range)
    {
        if (strpos($range, '-') !== false) {
            [$start, $end] = explode('-', $range);
            return [trim($start), trim($end)];
        }

        // Jika hanya satu IP
        $ip = trim($range);
        return [$ip, $ip];
    }

    public function sendEmailNotification(Request $request)
    {
        $user = auth()->user();
        $mikrotikId = $request->mikrotik_id;
        $now = now();

        // 🟢 1. Nonaktifkan pelanggan expired
        $expiredQuery = Customer::whereNotNull('expired_at')
            ->where('expired_at', '<=', $now)
            ->where('user_id', $user->id);

        if ($mikrotikId) {
            $expiredQuery->where('mikrotik_id', $mikrotikId);
        }
        $expiredCustomers = $expiredQuery->get();
        $expireAddSecondInitial = 3;
        // dd($expiredCustomers);
        foreach ($expiredCustomers as $customer) {
            // dd($customer);
            ProcessExpiredCustomers::dispatch('expire', $customer)->delay(now()->addSeconds($expireAddSecondInitial));
            $expireAddSecondInitial += 3; // Tambah delay 3 detik untuk setiap customer
        }

        $soonQuery = Customer::where('status', 'aktif')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<=', now()->addDays(3))
            ->where('expired_at', '>', now())
            // ->whereNull('notified_expiring_at')
            ->where('user_id', $user->id);

        if ($mikrotikId) {
            $soonQuery->where('mikrotik_id', $mikrotikId);
        }

        $soonToExpire = $soonQuery->get();
        $expireAddSecondInitial = 3;
        // Tambah delay 3 detik untuk setiap customer
        foreach ($soonToExpire as $customer) {
            ProcessExpiredCustomers::dispatch('expiring_soon', $customer)->delay(now()->addSeconds($expireAddSecondInitial));
            $expireAddSecondInitial += 3; // Tambah delay 3 detik untuk
        }

        //json response
        return response()->json([
            'message' => 'Proses penanganan kedaluwarsa telah dimulai di antrian.',
            'expired_count' => $expiredCustomers->count(),
            'soon_to_expire_count' => $soonToExpire->count(),
        ]);
    }

    public function bulkSendEmailNotification()
    {
        $user = auth()->user();
        $mikrotikIds = Mikrotik::pluck('id')->toArray();
        $now = now();

        // 🟢 1. Nonaktifkan pelanggan expired
        $expiredQuery = Customer::whereNotNull('expired_at')
            ->where('expired_at', '<=', $now)
            ->where('user_id', $user->id);
        foreach ($mikrotikIds as $mikrotikId) {
            if ($mikrotikId) {
                $expiredQuery->where('mikrotik_id', $mikrotikId);
            }
            $expiredCustomers = $expiredQuery->get();
            $expireAddSecondInitial = 3;
            // dd($expiredCustomers);
            foreach ($expiredCustomers as $customer) {
                // dd($customer);
                ProcessExpiredCustomers::dispatch('expire', $customer)->delay(now()->addSeconds($expireAddSecondInitial));
                $expireAddSecondInitial += 3; // Tambah delay 3 detik untuk setiap customer
            }

            $soonQuery = Customer::where('status', 'aktif')
                ->whereNotNull('expired_at')
                ->where('expired_at', '<=', now()->addDays(3))
                ->where('expired_at', '>', now())
                // ->whereNull('notified_expiring_at')
                ->where('user_id', $user->id);

            if ($mikrotikId) {
                $soonQuery->where('mikrotik_id', $mikrotikId);
            }

            $soonToExpire = $soonQuery->get();
            $expireAddSecondInitial = 3;
            // Tambah delay 3 detik untuk setiap customer
            foreach ($soonToExpire as $customer) {
                ProcessExpiredCustomers::dispatch('expiring_soon', $customer)->delay(now()->addSeconds($expireAddSecondInitial));
                $expireAddSecondInitial += 3; // Tambah delay 3 detik untuk
            }


        }

    }
}
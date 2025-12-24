<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Routing\Controller;
use App\Models\IpPool;
use App\Services\MikrotikApiService;
use Illuminate\Http\Request;

class IpPoolController extends Controller
{
    public function __construct()
    {
        // $this->middleware('role:mikrotik_admin');
    }

    public function index()
    {
        $ipPools = IpPool::where('user_id', auth()->id())->paginate(10);
        return view('tenant.ip_pool.index', compact('ipPools'));
    }

    public function create(Request $request)
    {
        $fromMikrotik = $request->query('from_mikrotik');
        return view('tenant.ip_pool.create', compact('fromMikrotik'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ip_pools,name,NULL,id,user_id,' . auth()->id(),
            'range' => 'required|string',
            'next_pool' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'mikrotik_id' => 'required|exists:mikrotiks,id', // wajib jika dari mikrotik.show
        ]);

        $mikrotik = \App\Models\Mikrotik::find($request->mikrotik_id);

        // 1. Simpan ke Laravel DB
        $ipPool = IpPool::create([
            'name' => $request->name,
            'range' => $request->range,
            'next_pool' => $request->next_pool,
            'description' => $request->description,
            'user_id' => auth()->id(),
            'mikrotik_id' => $mikrotik->id,
        ]);

        // 2. Sinkron ke MikroTik via API
        try {
            $api = new MikrotikApiService(
                $mikrotik->ip_address,
                $mikrotik->username,
                decrypt($mikrotik->password),
                $mikrotik->port
            );

            $api->addIpPool($request->name, $request->range, $request->next_pool);
        } catch (\Exception $e) {
            // Jika API gagal, hapus dari DB
            $ipPool->delete();
            return back()->with('error', 'Gagal simpan ke MikroTik: ' . $e->getMessage());
        }

        if ($request->filled('from_mikrotik')) {
            return redirect()
                ->route('tenant.mikrotik.show', $mikrotik)
                ->with('success', 'IP Pool berhasil dibuat di sistem dan MikroTik.');
        }

        return redirect()->route('tenant.ip_pool.index')
            ->with('success', 'IP Pool berhasil dibuat.');
    }

    public function edit(IpPool $ipPool)
    {
        $this->authorizePool($ipPool);
        return view('tenant.ip_pool.edit', compact('ipPool'));
    }

    public function update(Request $request, IpPool $ipPool)
    {
        $this->authorizePool($ipPool);
        // dd($request->all());

        $request->validate([
            'name' => 'required|string|max:255|unique:ip_pools,name,' . $ipPool->id . ',id,user_id,' . auth()->id(),
            'range' => 'required|string',
            'next_pool' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $mikrotik = $ipPool->mikrotik;
        $oldName = $ipPool->name;
        // 1. Update di Laravel DB
        $ipPool->update($request->only(['name', 'range', 'next_pool', 'description']));

        // 2. Sinkron ke MikroTik
        try {
            $api = new MikrotikApiService(
                $mikrotik->ip_address,
                $mikrotik->username,
                decrypt($mikrotik->password),
                $mikrotik->port
            );
            // dd($oldName);
            $api->updateIpPool($oldName, $request->name, $request->range, $request->next_pool);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update di MikroTik: ' . $e->getMessage());
        }

        if ($request->filled('from_mikrotik')) {
            return redirect()
                ->route('tenant.mikrotik.show', $request->from_mikrotik)
                ->with('success', 'IP Pool berhasil diperbarui di sistem dan MikroTik.');
        }

        return redirect()->route('tenant.ip_pool.index')
            ->with('success', 'IP Pool berhasil diperbarui.');
    }

    public function destroy(IpPool $ipPool, Request $request)
    {
        $this->authorizePool($ipPool);
        $mikrotik = $ipPool->mikrotik;

        // 1. Hapus dari MikroTik dulu
        try {
            $api = new MikrotikApiService(
                $mikrotik->ip_address,
                $mikrotik->username,
                decrypt($mikrotik->password),
                $mikrotik->port
            );

            $api->removeIpPool($ipPool->name);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal hapus dari MikroTik: ' . $e->getMessage());
        }

        // 2. Baru hapus dari Laravel
        $ipPool->delete();

        if ($request->filled('from_mikrotik')) {
            return redirect()
                ->route('tenant.mikrotik.show', $request->from_mikrotik)
                ->with('success', 'IP Pool berhasil dihapus dari MikroTik dan sistem.');
        }

        return redirect()->route('tenant.ip_pool.index')
            ->with('success', 'IP Pool berhasil dihapus.');
    }

    private function authorizePool(IpPool $ipPool)
    {
        if ($ipPool->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
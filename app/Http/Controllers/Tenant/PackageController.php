<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Routing\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function __construct()
    {
        // $this->middleware('role:mikrotik_admin');
    }

    public function index()
    {
        $packages = Package::where('user_id', auth()->id())->paginate(10);
        return view('tenant.package.index', compact('packages'));
    }

    public function create(Request $request)
    {
        $fromMikrotik = $request->query('from_mikrotik');
        return view('tenant.package.create', compact('fromMikrotik'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:pppoe,hotspot',
            'speed_up' => 'required|string',
            'speed_down' => 'required|string',
            'duration_days' => 'nullable|integer|min:1',
            'quota' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        $package = Package::create([
            'name' => $request->name,
            'mikrotik_id' => $request->mikrotik_id, // Pastikan ini ada jika dari mikrotik.show
            'type' => $request->type,
            'speed_up' => $request->speed_up,
            'speed_down' => $request->speed_down,
            'duration_days' => $request->duration_days,
            'quota' => $request->quota ? $request->quota * 1024 * 1024 * 1024 : null,
            'price' => $request->price,
            'description' => $request->description,
            'user_id' => auth()->id(),
        ]);

        // Redirect kembali ke mikrotik.show jika dari sana
        if ($request->filled('from_mikrotik')) {
            return redirect()
                ->route('tenant.mikrotik.show', $request->from_mikrotik)
                ->with('success', 'Paket berhasil ditambahkan.');
        }

        return redirect()->route('tenant.package.index')
            ->with('success', 'Paket berhasil ditambahkan.');
    }

    public function edit(Package $package)
    {
        $this->authorizePackage($package);
        return view('tenant.package.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $this->authorizePackage($package);

        $request->validate([
            'name' => 'required|string|max:255',
            'mikrotik_id' => 'required|exists:mikrotiks,id', // wajib jika dari mikrotik.show
            'type' => 'required|in:pppoe,hotspot',
            'speed_up' => 'required|string',
            'speed_down' => 'required|string',
            'duration_days' => 'nullable|integer|min:1',
            'quota' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'type', 'speed_up', 'speed_down', 'duration_days', 'description']);
        $data['price'] = $request->price;
        $data['quota'] = $request->quota ? $request->quota * 1024 * 1024 * 1024 : null;

        $package->update($data);

        if ($request->filled('from_mikrotik')) {
            return redirect()
                ->route('tenant.mikrotik.show', $request->from_mikrotik)
                ->with('success', 'Paket berhasil diperbarui.');
        }

        return redirect()->route('tenant.package.index')
            ->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(Package $package, Request $request)
    {
        $this->authorizePackage($package);
        $package->delete();

        if ($request->filled('from_mikrotik')) {
            return redirect()
                ->route('tenant.mikrotik.show', $request->from_mikrotik)
                ->with('success', 'Paket berhasil dihapus.');
        }

        return redirect()->route('tenant.package.index')
            ->with('success', 'Paket berhasil dihapus.');
    }

    private function authorizePackage(Package $package)
    {
        if ($package->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
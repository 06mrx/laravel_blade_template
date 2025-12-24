<?php

namespace App\Http\Controllers\Tenant;

use App\Models\BillingCycle;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Str;

class BillingCycleController extends Controller
{

    public function __construct()
    {
        // $this->middleware('role:tenant');
        $this->middleware('permission:view-billingcycle')->only(['index']);
        $this->middleware('permission:create-billingcycle')->only(['create', 'store']);
        $this->middleware('permission:edit-billingcycle')->only(['edit', 'update']);
        $this->middleware('permission:delete-billingcycle')->only(['destroy']);


    }

    public function index()
    {
        $billingCycles = BillingCycle::where('user_id', auth()->id())
            ->with('mikrotik')
            ->latest()
            ->paginate(10);

        return view('tenant.billing_cycle.index', compact('billingCycles'));
    }

    public function create()
    {
        return view('tenant.billing_cycle.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:fixed,segmented,anniversary',
            'due_days' => 'nullable|string',
            'mikrotik_id' => 'nullable|exists:mikrotiks,id',
            'is_default' => 'boolean',
        ]);

        $dueDays = [];
        if ($request->type !== 'anniversary') {
            $dueDays = array_map('intval', explode(',', $request->due_days));
            sort($dueDays);
            foreach ($dueDays as $day) {
                if ($day < 1 || $day > 31) {
                    return back()->withErrors(['due_days' => 'Tanggal harus antara 1-31.']);
                }
            }
        }

        $billingCycle = new BillingCycle();
        $billingCycle->id = (string) Str::uuid();
        $billingCycle->name = $request->name;
        $billingCycle->type = $request->type;
        $billingCycle->due_days = $dueDays;
        $billingCycle->mikrotik_id = $request->mikrotik_id;
        $billingCycle->user_id = auth()->id();
        $billingCycle->is_default = $request->has('is_default');

        // Jika di-set sebagai default, nonaktifkan default lain
        if ($billingCycle->is_default) {
            BillingCycle::where('mikrotik_id', $billingCycle->mikrotik_id)
                ->where('user_id', auth()->id())
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $billingCycle->save();

        return redirect()->route('tenant.billing_cycle.index')
            ->with('success', 'Skema penagihan berhasil dibuat.');
    }

    public function edit(BillingCycle $billingCycle)
    {
        // $this->authorize('update', $billingCycle);
        return view('tenant.billing_cycle.edit', compact('billingCycle'));
    }

    public function update(Request $request, BillingCycle $billingCycle)
    {
        // $this->authorize('update', $billingCycle);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:fixed,segmented,anniversary',
            'due_days' => 'nullable|string',
            'mikrotik_id' => 'nullable|exists:mikrotiks,id',
            'is_default' => 'boolean',
        ]);
        dd($billingCycle);
        $dueDays = [];
        if ($request->type !== 'anniversary') {
            $dueDays = array_map('intval', explode(',', $request->due_days));
            sort($dueDays);
            foreach ($dueDays as $day) {
                if ($day < 1 || $day > 31) {
                    return back()->withErrors(['due_days' => 'Tanggal harus antara 1-31.']);
                }
            }
        }

        $billingCycle->name = $request->name;
        $billingCycle->type = $request->type;
        $billingCycle->due_days = $dueDays;
        $billingCycle->mikrotik_id = $request->mikrotik_id;

        if ($request->has('is_default')) {
            // Nonaktifkan default lain
            BillingCycle::where('mikrotik_id', $billingCycle->mikrotik_id)
                ->where('user_id', auth()->id())
                ->where('is_default', true)
                ->update(['is_default' => false]);
            $billingCycle->is_default = true;
        } else {
            $billingCycle->is_default = false;
        }

        $billingCycle->save();

        return redirect()->route('tenant.billing_cycle.index')
            ->with('success', 'Skema penagihan berhasil diperbarui.');
    }

    public function destroy(BillingCycle $billingCycle)
    {
        // $this->authorize('delete', $billingCycle);

        // Jangan hapus jika sedang dipakai pelanggan
        if ($billingCycle->customers()->exists()) {
            return redirect()->back()->with('error', 'Tidak bisa dihapus karena sedang digunakan oleh pelanggan.');
        }

        $billingCycle->delete();

        return redirect()->route('tenant.billing_cycle.index')
            ->with('success', 'Skema penagihan berhasil dihapus.');
    }
}

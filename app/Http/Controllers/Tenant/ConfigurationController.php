<?php

namespace App\Http\Controllers\Tenant;

use App\Models\BillingCycle;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;
use Str;

class ConfigurationController extends Controller
{

    public function __construct()
    {
        // $this->middleware('role:tenant');
        $this->middleware('permission:view-configuration')->only(['index']);
        $this->middleware('permission:create-configuration')->only(['create', 'store']);
        $this->middleware('permission:edit-configuration')->only(['edit', 'update']);
        $this->middleware('permission:delete-configuration')->only(['destroy']);


    }
    public function index()
    {
        $config = Configuration::first();
        if (!$config) {
            $config = new Configuration();
        }

        return view('tenant.configuration.edit', compact('config'));
    }

    public function create()
    {
        $config = Configuration::first();
        if ($config) {
            return redirect()->route('tenant.configuration.index');
        }

        return view('tenant.configuration.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_logo' => 'nullable|image|max:2048',
            'midtrans_client_key' => 'nullable|string',
            'midtrans_server_key' => 'nullable|string',
            'payment_type_id' => 'required|string|in:manual,midtrans',
            'type' => 'required|in:fixed,segmented,anniversary',
            'due_days' => 'nullable|string',
        ]);

        $config = new Configuration();
        $config->id = (string) \Str::uuid();
        $config->business_name = $request->business_name;
        $config->midtrans_client_key = $request->midtrans_client_key;
        $config->midtrans_server_key = $request->midtrans_server_key;
        $config->payment_type_id = $request->payment_type_id;

        if ($request->hasFile('business_logo')) {
            $config->business_logo = $request->file('business_logo')->store('logos');
        }

        if($config->save()) {
            $billingCycle = new BillingCycle();
            $billingCycle->id = (string) Str::uuid();
            $billingCycle->name = "{$config->business_name} Billing Cycle";
            $billingCycle->type = $request->type;
            $billingCycle->due_days = array_map('intval', explode(',', $request->due_days));
            $billingCycle->is_default = true;
            $billingCycle->user_id = auth()->id();
            $billingCycle->save();
        }

        return redirect()->route('dashboard')->with('success', 'Konfigurasi berhasil dibuat.');
    }

    public function edit($id = null)
    {
        $config = Configuration::first();
        $billingCycle = BillingCycle::where('user_id', auth()->id())->first();
        if (!$config) {
            return redirect()->route('tenant.configuration.create');
        }

        return view('tenant.configuration.edit', compact('config', 'billingCycle'));
    }

    public function update(Request $request, $id)
    {
        $config = Configuration::first();
        if (!$config) {
            return redirect()->route('tenant.configuration.create');
        }

        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_logo' => 'nullable|image|max:2048',
            'midtrans_client_key' => 'nullable|string',
            'midtrans_server_key' => 'nullable|string',
            'payment_type_id' => 'required|string|in:manual,midtrans',
        ]);

        $config->business_name = $request->business_name;
        $config->midtrans_client_key = $request->midtrans_client_key;
        $config->midtrans_server_key = $request->midtrans_server_key;
        $config->payment_type_id = $request->payment_type_id;

        if ($request->hasFile('business_logo')) {
            // Hapus lama
            if ($config->business_logo && Storage::exists($config->business_logo)) {
                Storage::delete($config->business_logo);
            }
            $config->business_logo = $request->file('business_logo')->store('logos');
        }

        if($config->save()) {
            $billingCycle = BillingCycle::where('user_id', auth()->id())->first();
            // dd($billingCycle);
            // $billingCycle->id = (string) Str::uuid();
            // $billingCycle->name = "{$config->business_name} Billing Cycle";
            $billingCycle->type = $request->type;
            $billingCycle->due_days = array_map('intval', explode(',', $request->due_days));
            $billingCycle->is_default = true;
            $billingCycle->user_id = auth()->id();
            $billingCycle->save();
        }

        return redirect()->route('dashboard')->with('success', 'Konfigurasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $config = Configuration::findOrFail($id);
        $config->delete();

        return redirect()->route('tenant.configuration.create')->with('success', 'Konfigurasi berhasil dihapus.');
    }
}
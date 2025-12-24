<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Customer, Odc, Odp};
use Illuminate\Support\Facades\Validator;
class TechnicianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->mikrotik_id) {
             $customers = Customer::where('mikrotik_id', $request->mikrotik_id)
                ->where('status', 'terdaftar')
                ->with('package')
                ->orderBy('created_at', 'desc')
                ->get();
            // dd($customers);
            $odcs = Odc::where('mikrotik_id', $request->mikrotik_id)->with('odps')->get();
            return view('technician.index', compact('customers', 'odcs'));
        }
        return view('technician.index');
    }

    public function getOdps(Request $request) {

    }
    public function queueLists(Request $request)
    {
        $request->validate([
            'mikrotik_uuid' => 'required|uuid|exists:mikrotiks,id'
        ]);

        $customers = Customer::where('mikrotik_id', $request->mikrotik_uuid)
            ->where('status', 'terdaftar')
            ->with('package')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($c) {
                return [
                    'name' => $c->name,
                    'username' => $c->username,
                    'phone' => $c->phone,
                    'address' => $c->address,
                    'status' => $c->status,
                    'package_name' => $c->package?->name
                ];
            });

        return response()->json([
            'success' => true,
            'customers' => $customers
        ]);
    }

    public function updateCustomer(Request $request, $customer_id) {
        // dd($request->all());
         $validator = Validator::make($request->all(), [
            'odc_id' => 'required',
            'odp_id' => 'required',
            'port' => 'required|numeric',
            'maps_url' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $customer = Customer::findOrFail($customer_id);
        // dd($customer);
        $customer->odc_id = $request->odc_id;
        $customer->odp_id = $request->odp_id;
        $customer->port = $request->port;
        $customer->maps_url = $request->maps_url;
        $customer->save();

        return redirect()->route('technician.queue-lists', ['mikrotik_id' => $customer->mikrotik_id])->with('success', 'Data pelanggan berhasil diperbarui.');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

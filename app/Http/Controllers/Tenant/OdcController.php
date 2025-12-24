<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Mikrotik;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Odc;

class OdcController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-odc')->only(['index']);
        $this->middleware('permission:create-odc')->only(['create', 'store']);
        $this->middleware('permission:edit-odc')->only(['edit', 'update']);
        $this->middleware('permission:delete-odc')->only(['destroy']);
    }

    public function index()
    {
        $models = Odc::where('created_by', auth()->id())->paginate(10);
        
        return view('tenant.odc.index', compact('models'));
    }

    public function create()
    {
        $mikrotiks = Mikrotik::where('created_by', auth()->id())->get();
        return view('tenant.odc.create', compact('mikrotiks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'mikrotik_id' => 'required',
        ]);
        
        // $request->merge(['is_active' => true]);
        Odc::create(
            [
                'name' => $request->name,
                'mikrotik_id' => $request->mikrotik_id,]
        );
        return redirect()->route('tenant.odc.index')->with('success', 'Odc berhasil ditambahkan.');
    }

    public function edit(Odc $Odc)
    {
        $mikrotiks = Mikrotik::where('created_by', auth()->id())->get();
        return view('tenant.odc.edit', compact('Odc', 'mikrotiks'));
    }

    public function show(Odc $Odc)
    {
        return view('tenant.odc.show', compact('Odc'));
    }

    public function update(Request $request, Odc $Odc)
    {
        $request->validate([
            'name' => 'required',
            'mikrotik_id' => 'required',
            // 'account_number' => 'required',
        ]);

        // dd($request->all());

        $Odc->update(
            [
                'name' => $request->name,
                'mikrotik_id' => $request->mikrotik_id,
            ]
        );
        return redirect()->route('tenant.odc.index')->with('success', 'Odc berhasil ditambahkan.');
    }

    public function destroy(Odc $Odc)
    {
        $Odc->delete();
        return redirect()->route('tenant.odc.index')->with('success', 'Odc berhasil dihapus.');
    }
}

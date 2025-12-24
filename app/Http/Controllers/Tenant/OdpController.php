<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\{Odp, Odc};

class OdpController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-odp')->only(['index']);
        $this->middleware('permission:create-odp')->only(['create', 'store']);
        $this->middleware('permission:edit-odp')->only(['edit', 'update']);
        $this->middleware('permission:delete-odp')->only(['destroy']);
    }

    public function index()
    {
        $models = Odp::where('created_by', auth()->id())->paginate(10);
        
        return view('tenant.odc.odp.index', compact('models'));
    }

    public function create(Odc $odc)
    {
        return view('tenant.odc.odp.create', compact('odc'));
    }

    public function store(Request $request, Odc $odc)
    {
        // dd($odc->id);
        $request->validate([
            'name' => 'required',
            // 'odc_id' => 'required',
        ]);
        // $request->merge(['is_active' => true]);
        Odp::create(
            [
                'name' => $request->name,    
                'odc_id' => $odc->id,]
        );
        return redirect()->route('tenant.odc.odp.index', $odc)->with('success', 'Odp berhasil ditambahkan.');
    }

    public function edit(Odc $Odc, Odp $Odp)
    {
        return view('tenant.odc.odp.edit', compact('Odc', 'Odp'));
    }

    public function update(Request $request,Odc $Odc, Odp $Odp )
    {
        $request->validate([
            'name' => 'required',
            // 'odc_id' => 'required',
            // 'account_number' => 'required',
        ]);
        $Odp ->update($request->all());
        return redirect()->route('tenant.odc.show', [$Odc])->with('success', 'Odp berhasil ditambahkan.');
    }

    public function destroy(Odp $Odp )
    {
        $Odp ->delete();
        return redirect()->route('tenant.odc.odp.index')->with('success', 'Odp berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\BankAccount;
use App\Models\Configuration;
class BankAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-bankaccount')->only(['index']);
        $this->middleware('permission:create-bankaccount')->only(['create', 'store']);
        $this->middleware('permission:edit-bankaccount')->only(['edit', 'update']);
        $this->middleware('permission:delete-bankaccount')->only(['destroy']);
    }

    public function index()
    {
        $models = BankAccount::where('created_by', auth()->id())->paginate(10);
        
        return view('tenant.bank_account.index', compact('models'));
    }

    public function create()
    {
        return view('tenant.bank_account.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'owner' => 'required',
            'account_number' => 'required',
        ]);
        $request->merge(['is_active' => true]);
        BankAccount::create($request->all());
        return redirect()->route('tenant.bank_account.index')->with('success', 'Akun bank berhasil ditambahkan.');
    }

    public function edit(BankAccount $bankAccount)
    {
        return view('tenant.bank_account.edit', compact('bankAccount'));
    }

    public function update(Request $request, BankAccount $bankAccount)
    {
        $request->validate([
            'name' => 'required',
            'owner' => 'required',
            'account_number' => 'required',
        ]);
        if($request->has('is_active')) {
            $request->merge(['is_active' => true]);
        } else {
            $request->merge(['is_active' => false]);
        }
        $bankAccount->update($request->all());
        return redirect()->route('tenant.bank_account.index')->with('success', 'Akun bank berhasil ditambahkan.');
    }

    public function destroy(BankAccount $bankAccount)
    {
        $bankAccount->delete();
        return redirect()->route('tenant.bank_account.index')->with('success', 'Akun bank berhasil dihapus.');
    }
}

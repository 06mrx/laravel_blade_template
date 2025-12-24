<?php

namespace App\Http\Controllers\Tenant;


use App\Models\Customer;
use Illuminate\Routing\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Models\BankAccount;
use App\Models\BillingCycle;
use Illuminate\Support\Facades\DB;
use App\Services\MikrotikApiService;

class InvoiceController extends Controller
{

    public function __construct()
    {
        // $this->middleware('role:tenant');
        $this->middleware('permission:view-invoice')->only(['index']);
        $this->middleware('permission:show-invoice')->only(['show']);
        // $this->middleware('permission:mark-invoice')->only(['markAsPaid']);
        // $this->middleware('permission:delete-invoice')->only(['destroy']);


    }
    public function index()
    {

        $mikrotik_id = auth()->id();
        $invoices = Invoice::with('customer', 'package')
            ->whereHas('customer', function ($q) use ($mikrotik_id) {
                $q->where('mikrotik_id', $mikrotik_id);
            })
            ->latest('issue_date')
            ->paginate(10);
        // $configuration = Configuration::where('created_by', auth()->id())->first();
        // $bankAccounts = [];
        // if($configuration->payment_type_id == 'manual') {
        //     $bankAccounts = BankAccount::where('created_by', auth()->id())->get();
        // }

        return view('tenant.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $this->authorize($invoice);

        return view('tenant.invoices.show', compact('invoice'));
    }
    public function check(Request $request)
    {
        // Cari berdasarkan nomor invoice
        $invoices = Invoice::where('invoice_number', $request->code)
            // ->whereIn('status',)
            ->with('customer', 'package')
            ->get();


        // dd($invoices);
        // Jika tidak ketemu, coba cari berdasarkan username pelanggan
        if ($invoices->isEmpty()) {
            $customer = Customer::where('username', $request->code)->first();

            if ($customer) {
                $invoices = Invoice::where('customer_id', $customer->id)
                    // ->whereIn('status')
                    ->with('customer', 'package')
                    ->get();
            }
        }
        $code = $request->code;
        return view('invoices.check', compact('invoices', 'code'));
    }

    public function markAsPaid(Invoice $invoice)
    {
        // $this->authorize($invoice);
        // dd(1);
        $billingCycle =  BillingCycle::where('created_by', auth()->id())->where('is_default', true)->first();
        $registrationDate = now();
        $nextInvoiceDate = $billingCycle->getNextDueDate($registrationDate);
        // dd($nextInvoiceDate);
        $invoice->update(['status' => 'paid']);
        
        $invoice->customer->update([
            'expired_at' => $nextInvoiceDate,
            'next_invoice_date' => $nextInvoiceDate,
            'status' => 'aktif',
        ]);

        $radiusDB = DB::connection('mysql_radius');
        if ($invoice->customer->package) {
            $radiusDB->table('radreply')
                ->where('username', $invoice->customer->username)
                ->where('attribute', 'Mikrotik-Group')
                ->delete();

            $radiusDB->table('radreply')->updateOrInsert(
                ['username' => $invoice->customer->username, 'attribute' => 'Framed-Pool'],
                [
                    'op' => ':=',
                    'value' => $invoice->customer->ipPool->name,
                ]
            );
        }
        try {
            $api = new MikrotikApiService(
                $invoice->customer->mikrotik->ip_address,
                $invoice->customer->mikrotik->username,
                decrypt($invoice->customer->mikrotik->password),
                $invoice->customer->mikrotik->port
            );

           $api->kickPppActive($invoice->customer->username);
        } catch (Exception $e) {
            return redirect()
                ->route('tenant.mikrotik.show', $invoice->customer->mikrotik)
                ->with('error', 'Gagal sinkron: ' . $e->getMessage());
        }

        //  Invoice::create([
        //         'customer_id' => $invoice->customer->id,
        //         'package_id' => $invoice->customer->package->id,
        //         'amount' => $amount ?? 0,
        //         'issue_date' => now(),
        //         'due_date' => $invoice->customer->expired_at,
        //         'status' => 'unpaid',
        //         'notes' => 'Tagihan perpanjangan paket.',
        //     ]);

        return redirect()->back()->with('success', 'Invoice ditandai sebagai dibayar.');
    }

    private function authorize(Invoice $invoice)
    {
        if ($invoice->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
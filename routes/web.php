<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tenant\BillingCycleController;
use App\Http\Controllers\Tenant\WebProxyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BayiController;
use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Tenant\MikrotikController;
use App\Http\Controllers\Tenant\PackageController;
use App\Http\Controllers\Tenant\IpPoolController;
use App\Http\Controllers\Tenant\CustomerController;
use App\Http\Controllers\Tenant\NotificationController;
use App\Http\Controllers\Tenant\ConfigurationController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\InvoiceController;
use App\Http\Controllers\Tenant\BankAccountController;
use App\Http\Controllers\Tenant\OdcController;
use App\Http\Controllers\Tenant\OdpController;
use App\Http\Controllers\TechnicianController;
// use Illuminate\Support\Facades\Artisan;
// use Illuminate\Http\Request;
// use App\Jobs\ProcessExpiredCustomers;


Route::get('/', function () {
    return view('welcome');
})->name('/');

// Route::post('/tenant/mikrotik/run-expire', function (Request $request) {
//     $mikrotik = \App\Models\Mikrotik::findOrFail($request->mikrotik_id);

//     if ($mikrotik->created_by !== auth()->id()) {
//         return response()->json(['message' => 'Unauthorized'], 403);
//     }

//     ProcessExpiredCustomers::dispatch(auth()->user(), $mikrotik->id);
//     //


//     return response()->json([
//         'message' => 'Proses penanganan kedaluwarsa telah dimulai di latar belakang.'
//     ]);
// })->name('tenant.mikrotik.run-expire');
Route::post('/tenant/mikrotik/send-mail-notification', [MikrotikController::class, 'sendEmailNotification'])->name('tenant.mikrotik.send-mail-notification');
Route::get('/tenant/mikrotik/bulk-send-mail-notification', [MikrotikController::class, 'bulkSendEmailNotification'])->name('tenant.mikrotik.bulk-send-mail-notification');


Route::get('/tenant/mikrotik/tutorial', function () {
    return view('tenant.mikrotik.tutorial');
})->name('tenant.mikrotik.tutorial');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth'])->prefix('admin')->as('admin.')->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('users', UserController::class);
    Route::resource('audit', AuditController::class)->only(['index', 'show']);

});

Route::middleware(['auth'])->prefix('tenant')->as('tenant.')->group(function () {
    Route::resource('mikrotik', MikrotikController::class);
    Route::resource('package', PackageController::class);
    Route::resource('ip_pool', IpPoolController::class);
    Route::resource('customer', CustomerController::class);

    Route::get('tenant/customer/toggle-status', [
        CustomerController::class,
        'toggleStatus'
    ])->name('customer.toggle-status');

    Route::post('tenant/mikrotik/{mikrotik}/sync-customers', [
        MikrotikController::class,
        'syncCustomers'
    ])->name('mikrotik.sync-customers');
    Route::post('tenant/mikrotik/{mikrotik}/sync-ippools', [
        MikrotikController::class,
        'syncIpPools'
    ])->name('mikrotik.sync-ippools');
    Route::resource('configuration', ConfigurationController::class)->except(['show', 'destroy']);
    // Route::get('/configuration', [ConfigurationController::class, 'index'])->name('configuration.index');
    Route::resource('bank_account', BankAccountController::class);
    Route::resource('billing_cycle', BillingCycleController::class);

    Route::resource('odc', OdcController::class);
    Route::resource('odc.odp', OdpController::class);

    //webproxy
    Route::get('/mikrotik/{mikrotik}/webproxy', [WebProxyController::class, 'edit'])->name('webproxy.edit');
    Route::put('/mikrotik/{mikrotik}/webproxy', [WebProxyController::class, 'update'])->name('webproxy.update');
    Route::get('/mikrotik/{mikrotik}/webproxy/preview', [WebProxyController::class, 'preview'])->name('webproxy.preview');

    Route::get('/invoices/markaspaid/{invoice}', [InvoiceController::class, 'markAsPaid'])->name('invoices.markAsPaid');
}); 

Route::get('/test-email', function () {
    $customer = App\Models\Customer::first();
    $customer->notify(new App\Notifications\CustomerExpiredNotification($customer));
    return 'Email dikirim!';
});

Route::get('/test-expiring', function () {
    $customer = App\Models\Customer::whereNotNull('expired_at')
        ->where('is_active', true)
        ->first();
    // dd($customer);

    if ($customer) {
        $customer->notify(new App\Notifications\CustomerExpiringSoonNotification($customer));
        return 'Notifikasi "akan expired" dikirim!';
    }

    return 'Tidak ada pelanggan dengan expired_at.';
});

Route::post('/tenant/notifications/send', [NotificationController::class, 'send'])->name('tenant.notifications.send');
Route::get('/technician', [TechnicianController::class, 'index'])->name('technician.queue-lists');
Route::put('/technician/{customer_id}', [TechnicianController::class, 'updateCustomer'])->name('technician.update-customer');
Route::post('/technician/queue-lists', [TechnicianController::class, 'queueLists'])->name('technician.queue-lists.post');
Route::get('/invoices/check/', [InvoiceController::class, 'check'])->name('invoices.check');
// Route::resource('bayi', BayiController::class);
// // Route untuk restore data yang di-soft delete
// Route::post('bayi/{id}/restore', [BayiController::class, 'restore'])->name('bayi.restore');
require __DIR__ . '/auth.php';

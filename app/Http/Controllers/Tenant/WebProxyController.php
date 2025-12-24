<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Mikrotik;
use App\Services\MikrotikApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebProxyController extends Controller
{
    public function edit(Mikrotik $mikrotik)
    {
        $this->authorizeMikrotik($mikrotik);

        $path = "webproxy/{$mikrotik->id}/error.html";
        $html = '';


        if (Storage::exists($path)) {
            $html = Storage::get($path);
        } else {
            // Template default
            $html = $this->getDefaultTemplate();
        }
        // dd($html);
        return view('tenant.webproxy.edit', compact('mikrotik', 'html'));
    }

    public function update(Request $request, Mikrotik $mikrotik)
    {
        $this->authorizeMikrotik($mikrotik);

        $request->validate([
            'html_content' => 'required|string',
        ]);

        $path = "webproxy/{$mikrotik->id}/error.html";
        $content = $request->html_content;

        // Sanitize? (opsional, hati-hati dengan script)
        // Tapi biarkan tenant bisa tambah JS untuk tracking

        Storage::put($path, $content);


        // --- Kirim ke MikroTik via API ---
        try {
            $api = new MikrotikApiService(
                $mikrotik->ip_address,
                $mikrotik->username,
                decrypt($mikrotik->password),
                $mikrotik->port
            );

            // Pastikan Web Proxy aktif
            if (!$api->isWebProxyEnabled()) {
                $api->enableWebProxy();
            }

            // Generate URL public untuk file ini
            $publicUrl = Storage::url($path);
            // $fullUrl = url($publicUrl); // https://app.nexa.id/storage/webproxy/uuid/error.html
            $fullUrl = "http://192.168.126.40/xBilling/public/storage/webproxy/6dc7a895-8570-40e7-bc21-20a9c117426d/error.html";
            // Tambahkan timestamp agar tidak kena cache
            $fullUrl .= '?v=' . time();

            // Upload ke MikroTik
            $api->uploadFileFromUrl($fullUrl, '/webproxy/error.html');

            return redirect()->back()->with('success', 'HTML Web Proxy berhasil diperbarui dan dikirim ke MikroTik.');
        } catch (\Exception $e) {
            \Log::error("Gagal upload ke MikroTik: " . $e->getMessage());
            return redirect()->back()->with('error', 'File disimpan, tapi gagal kirim ke MikroTik: ' . $e->getMessage());
        }


        // return redirect()->back()->with('success', 'HTML Web Proxy berhasil diperbarui.');
    }

    private function getDefaultTemplate()
    {
        return view('tenant.webproxy.templates.default')->render();
    }

    public function preview(Mikrotik $mikrotik)
    {
        $path = "webproxy/{$mikrotik->id}/error.html";

        if (Storage::exists($path)) {
            $html = Storage::get($path);
        } else {
            $html = $this->getDefaultTemplate();
        }

        return response($html)->header('Content-Type', 'text/html');
    }

    private function authorizeMikrotik(Mikrotik $mikrotik)
    {
        if ($mikrotik->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
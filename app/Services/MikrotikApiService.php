<?php

namespace App\Services;

use RouterOS\Client;
use RouterOS\Query;
use Exception;

class MikrotikApiService
{
    protected $client;

    public function __construct($ip, $username, $password, $port = 8728)
    {
        try {
            // dd(vars: $ip,$username,$password,$port);
            $this->client = new Client([
                'host' => $ip,
                'user' => $username,
                'pass' => $password,
                'port' => $port,
            ]);
        } catch (Exception $e) {
            throw new Exception('Gagal terhubung ke MikroTik: ' . $e->getMessage());
        }
    }

    /**
     * Pastikan semua komponen isolir ada di MikroTik
     */
    public function ensureIsolirSetup($mikrotik)
    {
        $this->ensureIpPool();
        $this->ensurePppProfile($mikrotik);
        $this->ensureAddressList();
        $this->ensureWebProxy();
        $this->ensureFirewallNat();
        $this->ensureFirewallFilter();
    }

    // 1. IP Pool: pool-isolir
    private function ensureIpPool()
    {
        $query = new Query('/ip/pool/print');
        $query->where('name', 'pool-isolir');
        $result = $this->client->query($query)->read();

        if (empty($result)) {
            $addQuery = new Query('/ip/pool/add');
            $addQuery->equal('name', 'pool-isolir')
                ->equal('ranges', '172.16.0.2-172.16.0.254');
            $this->client->query($addQuery)->read();
        }
    }

    // 2. PPP Profile: isolir
    private function ensurePppProfile($mikrotik)
    {
        $query = new Query('/ppp/profile/print');
        $query->where('name', 'isolir');
        $result = $this->client->query($query)->read();
        // dd($result);
        if (empty($result)) {
            $addQuery = new Query('/ppp/profile/add');
            // dd(1);
            $addQuery->equal('name', 'isolir')
                ->equal('local-address', '172.16.0.1')
                ->equal('remote-address', 'pool-isolir')
                ->equal('dns-server', $mikrotik->ip_address) // IP MikroTik
                ->equal('rate-limit', '10M/10M')
                ->equal('address-list', 'ISOLIR-LIST'); // Tambahkan ke address list
            $this->client->query($addQuery)->read();
            // dd($res);
        }
    }

    // 3. Address List: ISOLIR-LIST
    private function ensureAddressList()
    {
        $query = new Query('/ip/firewall/address-list/print');
        $query->where('list', 'ISOLIR-LIST');
        $result = $this->client->query($query)->read();
        // dd(empty($result));

        if (empty($result)) {
            $addQuery = new Query('/ip/firewall/address-list/add');
            $addQuery->equal('list', 'ISOLIR-LIST')
                ->equal('address', '172.16.0.0/24')
                ->equal('comment', 'Pelanggan expired');
            $res = $this->client->query($addQuery)->read();
            // dd($res);
        }
    }

    // 4. Web Proxy: aktifkan
    private function ensureWebProxy()
    {
        $query = new Query('/ip/proxy/print');
        $result = $this->client->query($query)->read();
        // dd($result[0]);
        if (isset($result[0]['enabled']) && $result[0]['enabled'] === 'false') {
            // dd('disabled');
            $setQuery = new Query('/ip/proxy/set');
            $setQuery->equal('enabled', 'true')
                ->equal('port', '8090');
            $this->client->query($setQuery)->read();
            // dd($res);
        } elseif (empty($result)) {
            $addQuery = new Query('/ip/proxy/add');
            $addQuery->equal('enabled', 'true')
                ->equal('port', '8090');
            $this->client->query($addQuery)->read();
        }

        $query = new Query('/ip/proxy/access/print');
        $result = $this->client->query($query)->read();
        if (empty($result)) {
            $addQuery = new Query('/ip/proxy/access/add');
            $addQuery->equal('src-address', '172.16.0.0/24')
                ->equal('dst-address', '!172.16.0.1')
                ->equal('action', 'deny')
                ->equal('redirect-to', '172.16.0.1:8090')
                ->equal('comment', 'Block access to proxy for expired users');
            $this->client->query($addQuery)->read();
        }
    }

    // 5. Firewall NAT: redirect HTTP/HTTPS ke Web Proxy
    private function ensureFirewallNat()
    {
        $chain = 'dstnat';
        $protocol = 'tcp';
        $dstPorts = '80,443';
        $srcList = 'ISOLIR-LIST';
        $action = 'redirect';
        $toPort = '8090';

        $query = new Query('/ip/firewall/nat/print');
        $query->where('chain', $chain)
            ->where('protocol', $protocol)
            ->where('dst-port', $dstPorts)
            ->where('src-address-list', $srcList)
            ->where('action', $action)
            ->where('to-ports', $toPort);

        $result = $this->client->query($query)->read();
        // dd($result);
        if (empty($result)) {
            $addQuery = new Query('/ip/firewall/nat/add');
            $addQuery->equal('chain', $chain)
                ->equal('protocol', $protocol)
                ->equal('dst-port', $dstPorts)
                ->equal('src-address-list', $srcList)
                ->equal('action', $action)
                ->equal('to-ports', $toPort);
            $this->client->query($addQuery)->read();
        }
    }

    // 6. Firewall Filter: drop forward
    private function ensureFirewallFilter()
    {
        $chain = 'forward';
        $srcList = 'ISOLIR-LIST';
        $action = 'drop';
        $comment = 'Block internet for expired users';

        $query = new Query('/ip/firewall/filter/print');
        $query->where('chain', $chain)
            ->where('src-address-list', $srcList)
            ->where('action', $action);

        $result = $this->client->query($query)->read();
        // dd($result);
        if (empty($result)) {
            $addQuery = new Query('/ip/firewall/filter/add');
            $addQuery->equal('chain', $chain)
                ->equal('src-address-list', $srcList)
                ->equal('action', $action)
                ->equal('comment', $comment);
            $this->client->query($addQuery)->read();
        }
    }

    /**
     * Ganti port API MikroTik
     */
    public function changeApiPort($newPort)
    {
        // Cari service 'api'
        $query = new Query('/ip/service/print');
        $query->where('name', 'api');
        $result = $this->client->query($query)->read();

        if (empty($result)) {
            throw new \Exception("Service 'api' tidak ditemukan di MikroTik.");
        }

        $id = $result[0]['.id'];

        // Update port
        $updateQuery = new Query('/ip/service/set');
        $updateQuery->equal('.id', $id);
        $updateQuery->equal('port', $newPort);

        $this->client->query($updateQuery)->read();
    }

    /**
     * Ambil semua PPPoE Secret (pelanggan)
     */
    public function getPPPSecrets()
    {
        $query = new Query('/ppp/secret/print');
        return $this->client->query($query)->read();
    }



    /**
     * Ganti PPP Secret profile (untuk isolir atau aktifkan kembali)
     */
    public function updatePppSecretProfile($username, $newProfile)
    {
        $query = new Query('/ppp/secret/print');
        $query->where('name', $username);
        $result = $this->client->query($query)->read();

        if (empty($result)) {
            throw new \Exception("PPP Secret '$username' tidak ditemukan di MikroTik.");
        }

        $id = $result[0]['.id'];

        $updateQuery = new Query('/ppp/secret/set');
        $updateQuery->equal('.id', $id);
        $updateQuery->equal('profile', $newProfile);

        $this->client->query($updateQuery)->read();
    }

    /**
     * Cek apakah PPP Secret ada
     */
    public function pppSecretExists($username)
    {
        $query = new Query('/ppp/secret/print');
        $query->where('name', $username);
        $result = $this->client->query($query)->read();
        return !empty($result);
    }

    /**
     * Ambil semua IP Pool dari MikroTik
     */
    public function getIpPools()
    {
        $query = new Query('/ip/pool/print');
        return $this->client->query($query)->read();
    }
    /**
     * Tambah IP Pool di MikroTik
     */
    public function addIpPool($name, $ranges, $nextPool = null)
    {
        $query = new Query('/ip/pool/add');
        $query->equal('name', $name);
        $query->equal('ranges', $ranges);
        if ($nextPool) {
            $query->equal('next-pool', $nextPool);
        }
        $this->client->query($query)->read();
    }

    /**
     * Buat IP Pool isolir
     */
    public function createIsolirIpPool()
    {
        $name = 'pool-isolir';
        // $ranges = '172.16.0/24'; // Contoh range isolir
        $ranges = '172.16.0.2-172.16.0.254';
        $this->addIpPool($name, $ranges);
    }
    /**
     * Cek apakah IP Pool ada
     */
    public function ipPoolExists($name)
    {
        $query = new Query('/ip/pool/print');
        $query->where('name', $name);
        $result = $this->client->query($query)->read();
        return !empty($result);
    }

    /**
     * Update IP Pool di MikroTik
     */
    public function updateIpPool($oldName, $newName, $ranges, $nextPool = null)
    {

        $findQuery = new Query('/ip/pool/print');
        // dd($oldName);
        $findQuery->where('name', $oldName);
        $result = $this->client->query($findQuery)->read();
        // dd($result);
        if (empty($result)) {
            throw new \Exception("IP Pool '$oldName' tidak ditemukan di MikroTik.");
        }


        $id = $result[0]['.id'];
        // dd($result);
        $query = new Query('/ip/pool/set');
        $query->equal('.id', $id);
        // dd($newName);
        $query->equal('name', $newName);
        $query->equal('ranges', $ranges);
        // $query->equal('next-pool', $nextPool ?? '');

        $this->client->query($query)->read();
    }


    /**
     * Hapus IP Pool dari MikroTik
     */
    public function removeIpPool($name)
    {
        $findQuery = new Query('/ip/pool/print');
        $findQuery->where('name', $name);
        $result = $this->client->query($findQuery)->read();

        if (empty($result)) {
            return; // tidak ada, skip
        }

        $id = $result[0]['.id'];
        $query = new Query('/ip/pool/remove');
        $query->equal('.id', $id);
        $this->client->query($query)->read();
    }

    /**
     * Ambil daftar PPP Active (PPPoE yang sedang terhubung)
     */
    public function getPppActive()
    {
        try {
            $query = new Query('/ppp/active/print');
            $response = $this->client->query($query)->read();
            return $this->formatPppActive($response);
        } catch (\Exception $e) {
            \Log::error("Gagal ambil PPP Active: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Format data PPP Active agar lebih rapi
     */
    private function formatPppActive($data)
    {
        return array_map(function ($item) {
            return [
                'name' => $item['name'] ?? '-',
                'address' => $item['address'] ?? '-',
                'uptime' => $item['uptime'] ?? '-',
                'caller_id' => $item['caller-id'] ?? '-',
                'encoding' => $item['encoding'] ?? '-',
                'service' => $item['service'] ?? '-',
                'session_id' => $item['session-id'] ?? '-',
            ];
        }, $data);
    }


    /**
     * Kick / Disconnect PPP Active session by username
     */
    public function kickPppActive($username)
    {
        $query = new Query('/ppp/active/print');
        $query->where('name', $username);
        $sessions = $this->client->query($query)->read();

        foreach ($sessions as $session) {
            $id = $session['.id'];
            $removeQuery = new Query('/ppp/active/remove');
            $removeQuery->equal('.id', $id);
            $res = $this->client->query($removeQuery)->read();
            // dd($res);   
        }


    }

    // cek profile exist
    public function profileExists($profileName)
    {
        $query = new Query('/ppp/profile/print');
        $query->where('name', $profileName);
        $result = $this->client->query($query)->read();
        return !empty($result);
    }

    /**
     * Buat PPP Profile baru
     */
    public function createPppProfile($name, $localAddress, $remoteAddress, $dns, $rateLimit = null, $addressList = null)
    {
        if ($this->profileExists($name)) {
            throw new \Exception("Profile '$name' sudah ada di MikroTik.");
        }

        $query = new Query('/ppp/profile/add');
        $query->equal('name', $name);
        $query->equal('local-address', $localAddress);
        $query->equal('remote-address', $remoteAddress);
        $query->equal('dns-server', $dns);

        if ($rateLimit) {
            $query->equal('rate-limit', $rateLimit);
        }
        if ($addressList) {
            $query->equal('list', $addressList);
        }

        $this->client->query($query)->read();
    }
    // buat profile isolir
    public function createIsolirProfile($mikrotik)
    {
        $name = 'isolir';
        $localAddress = '172.16.0.1';
        $remoteAddress = 'pool-isolir';
        $dns = $mikrotik->ip_address; // gunakan IP MikroTik sebagai DNS
        $this->createPppProfile($name, $localAddress, $remoteAddress, $dns);
    }

    /**
     * Upload file dari URL eksternal ke MikroTik (via /tool/fetch)
     */
    public function uploadFileFromUrl($url, $destinationPath)
    {
        try {
            // Hapus file lama jika ada
            $query = new Query('/file/remove');
            $query->equal('name', $destinationPath);
            $this->client->query($query)->read();
            // $this->client->query(new Query('/file/remove'))->equal('name', $destinationPath)->read();

            // Download file baru dari URL
            $query = new Query('/tool/fetch');
            $query->equal('url', $url)
                ->equal('dst-path', $destinationPath)
                ->equal('mode', 'http');

            $result = $this->client->query($query)->read();

            // Cek apakah sukses
            if (isset($result[0]['status']) && $result[0]['status'] === 'finished') {
                return true;
            }

            throw new Exception('Gagal download: ' . json_encode($result));
        } catch (Exception $e) {
            throw new Exception("Upload file gagal: " . $e->getMessage());
        }
    }

    /**
     * Cek apakah Web Proxy aktif
     */
    public function isWebProxyEnabled()
    {
        $query = new Query('/ip/proxy/print');
        $result = $this->client->query($query)->read();

        return isset($result[0]['enabled']) && $result[0]['enabled'] === 'yes';
    }

    /**
     * Aktifkan Web Proxy jika belum aktif
     */
    public function enableWebProxy($port = 8090)
    {
        $query = new Query('/ip/proxy/set');
        $query->equal('enabled', 'yes')
            ->equal('port', $port);

        $this->client->query($query)->read();
    }
}
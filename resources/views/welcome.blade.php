<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXA - Network Exchange & Accounting</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .feature-card {
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.3);
        }

        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header/Navigation -->
    <nav class="fixed w-full z-50 glass-effect">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-network-wired text-blue-500 text-lg"></i>
                        </div>
                        <span class="text-white text-xl font-bold">NEXA</span>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="text-white hover:text-blue-200 transition-colors">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-white hover:text-blue-200 transition-colors">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="bg-white text-blue-500 px-4 py-2 rounded-lg font-medium hover:bg-blue-50 transition-colors">Daftar</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg min-h-screen flex items-center pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                        Kelola Network &
                        <span class="text-sky-200">Billing</span>
                        dengan Mudah
                    </h1>
                    <p class="text-xl mb-8 text-blue-100 leading-relaxed">
                        NEXA adalah solusi lengkap untuk manajemen jaringan dan billing ISP.
                        Otomasi billing, kontrol bandwidth, dan kelola pelanggan dalam satu platform.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="bg-white text-blue-600 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-blue-50 transition-all transform hover:scale-105 text-center">
                                Mulai Gratis
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        @endif
                        {{-- <button class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white hover:text-blue-600 transition-all text-center">
                            Lihat Demo
                            <i class="fas fa-play ml-2"></i>
                        </button> --}}
                    </div>

                </div>


                <div class="relative">
                    <div class="floating-animation">
                        <div class="bg-white p-8 rounded-2xl shadow-2xl">
                            <div class="text-center mb-6">
                                <div
                                    class="w-16 h-16 bg-gradient-to-r from-sky-500 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-chart-line text-white text-2xl"></i>
                                </div>
                                <h3 class="text-gray-800 font-semibold text-lg">Dashboard Analytics</h3>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Pelanggan Aktif</span>
                                    <span class="text-blue-600 font-bold">1,248</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Tagihan Bulan Ini</span>
                                    <span class="text-green-600 font-bold">Rp 45.6M</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Bandwidth Usage</span>
                                    <span class="text-sky-600 font-bold">87%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-sky-500 to-blue-500 h-2 rounded-full pulse-animation"
                                        style="width: 87%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="mt-12">
                <!-- Search Invoice Section -->
                <div
                    class="bg-white bg-opacity-10 backdrop-blur-lg rounded-3xl border mb-4 border-white border-opacity-20 p-8 shadow-2xl">
                    <div class="text-center mb-8">
                        <div
                            class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-search text-white text-2xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-white mb-2">Cek Status Tagihan</h2>
                        <p class="text-blue-100">Masukkan nomor pelanggan atau kode invoice untuk melihat tagihan Anda
                        </p>
                    </div>

                    <form action="{{ route('invoices.check') }}" method="GET" class="max-w-2xl mx-auto">
                        <div class="relative">
                            <div class="flex flex-col sm:flex-row gap-3">
                                <!-- Input Field -->
                                <div class="flex-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-file-invoice text-gray-400"></i>
                                    </div>
                                    <input type="text" name="code" id="code"
                                        placeholder="Nomor Invoice / Kode Pelanggan"
                                        class="w-full pl-12 pr-4 py-4 bg-white rounded-xl border-2 border-transparent focus:border-sky-300 focus:ring-4 focus:ring-sky-200 focus:outline-none text-gray-800 placeholder-gray-400 text-lg transition-all"
                                        required>
                                </div>

                                <!-- Search Button -->
                                <button type="submit"
                                    class="px-8 py-4 bg-white text-blue-600 rounded-xl font-semibold hover:bg-blue-50 transition-all transform hover:scale-105 shadow-lg flex items-center justify-center min-w-fit">
                                    <i class="fas fa-search mr-2"></i>
                                    <span class="hidden sm:inline">Cari Tagihan</span>
                                    <span class="sm:hidden">Cari</span>
                                </button>
                            </div>
                        </div>

                        <!-- Helper Text -->
                        <div class="mt-4 text-center">
                            <p class="text-blue-100 text-sm">
                                <i class="fas fa-lightbulb mr-2"></i>
                                Tip: Anda bisa mencari menggunakan nomor invoice (INV-xxx) atau kode pelanggan
                            </p>
                        </div>
                    </form>

                    <!-- Quick Search Examples -->
                    <div class="mt-6 flex flex-wrap justify-center gap-2">
                        <span class="text-blue-200 text-sm">Contoh pencarian:</span>
                        <button onclick="fillExample('INV-2024-001')"
                            class="text-xs bg-white bg-opacity-20 text-white px-3 py-1 rounded-full hover:bg-opacity-30 transition-all">
                            INV-2024-001
                        </button>
                        <button onclick="fillExample('PLG001')"
                            class="text-xs bg-white bg-opacity-20 text-white px-3 py-1 rounded-full hover:bg-opacity-30 transition-all">
                            PLG001
                        </button>
                        <button onclick="fillExample('CUST-12345')"
                            class="text-xs bg-white bg-opacity-20 text-white px-3 py-1 rounded-full hover:bg-opacity-30 transition-all">
                            CUST-12345
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Fitur Unggulan NEXA</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Tiga fitur utama yang akan mengubah cara Anda mengelola bisnis ISP
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1: Manajemen Pelanggan -->
                <div class="feature-card bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                    <div
                        class="w-16 h-16 bg-gradient-to-r from-sky-500 to-blue-500 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Manajemen Pelanggan</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Kelola data pelanggan, paket layanan, dan histori pembayaran dengan mudah di satu tempat.
                        Interface yang intuitif memudahkan tracking semua informasi pelanggan.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Database pelanggan lengkap
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Manajemen paket layanan
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Histori pembayaran detail
                        </li>
                    </ul>
                </div>

                <!-- Feature 2: Otomasi Billing -->
                <div class="feature-card bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                    <div
                        class="w-16 h-16 bg-gradient-to-r from-sky-500 to-blue-500 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-file-invoice-dollar text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Otomasi Billing</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Buat tagihan otomatis setiap bulan dan kirim notifikasi pembayaran ke pelanggan.
                        Hemat waktu dan kurangi kesalahan manual dengan sistem billing otomatis.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Tagihan otomatis bulanan
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Notifikasi WhatsApp & Email
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Payment gateway terintegrasi
                        </li>
                    </ul>
                </div>

                <!-- Feature 3: Kontrol Bandwidth -->
                <div class="feature-card bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                    <div
                        class="w-16 h-16 bg-gradient-to-r from-sky-500 to-blue-500 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-tachometer-alt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Kontrol Bandwidth</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Integrasi dengan Mikrotik memungkinkan Anda mengatur dan memantau penggunaan bandwidth secara
                        real-time.
                        Kontrol penuh atas jaringan Anda.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Integrasi Mikrotik API
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Monitoring real-time
                        </li>
                        <li class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Bandwidth allocation
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 gradient-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white mb-4">Dipercaya oleh ISP di Seluruh Indonesia</h2>
                <p class="text-xl text-blue-100">Bergabunglah dengan ribuan ISP yang sudah merasakan manfaat NEXA</p>
            </div>

            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center text-white">
                    <div class="text-5xl font-bold mb-2">500+</div>
                    <div class="text-blue-200">ISP Terdaftar</div>
                </div>
                <div class="text-center text-white">
                    <div class="text-5xl font-bold mb-2">50K+</div>
                    <div class="text-blue-200">Pelanggan Dikelola</div>
                </div>
                <div class="text-center text-white">
                    <div class="text-5xl font-bold mb-2">99.9%</div>
                    <div class="text-blue-200">Uptime</div>
                </div>
                <div class="text-center text-white">
                    <div class="text-5xl font-bold mb-2">24/7</div>
                    <div class="text-blue-200">Support</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-gray-800 mb-6">
                Siap Mengoptimalkan Bisnis ISP Anda?
            </h2>
            <p class="text-xl text-gray-600 mb-8">
                Bergabunglah dengan NEXA hari ini dan rasakan perbedaannya dalam mengelola jaringan dan billing.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="bg-gradient-to-r from-sky-500 to-blue-500 text-white px-8 py-4 rounded-xl font-semibold text-lg hover:from-sky-600 hover:to-blue-600 transition-all transform hover:scale-105">
                        Mulai Gratis Sekarang
                        <i class="fas fa-rocket ml-2"></i>
                    </a>
                @endif
                <button
                    class="border-2 border-blue-500 text-blue-500 px-8 py-4 rounded-xl font-semibold text-lg hover:bg-blue-500 hover:text-white transition-all">
                    Hubungi Sales
                    <i class="fas fa-phone ml-2"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <div
                            class="w-8 h-8 bg-gradient-to-r from-sky-500 to-blue-500 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-network-wired text-white"></i>
                        </div>
                        <span class="text-xl font-bold">NEXA</span>
                    </div>
                    <p class="text-gray-400">
                        Solusi lengkap untuk manajemen jaringan dan billing ISP modern.
                    </p>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Produk</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Manajemen Pelanggan</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Otomasi Billing</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Kontrol Bandwidth</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Analytics</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Perusahaan</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Karir</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Kontak</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4">Dukungan</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Dokumentasi</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Tutorial</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Support Ticket</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 NEXA. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling untuk anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Navbar background change on scroll
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 100) {
                nav.style.background = 'rgba(14, 165, 233, 0.95)';
            } else {
                nav.style.background = 'rgba(255, 255, 255, 0.1)';
            }
        });
    </script>
    <script>
        function fillExample(example) {
            document.getElementById('code').value = example;
            document.getElementById('code').focus();
        }

        // Auto-focus pada input saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            const queryString = window.location.search;

            // Buat object URLSearchParams
            const params = new URLSearchParams(queryString);

            // Ambil parameter tertentu
            const code = params.get('code'); // "123"
            // const type = params.get('type'); // "admin"

            // Tampilkan
            // console.log(user, type);
            if (code) {
                document.getElementById('code').focus();
                // alert('focus')
            }
            // document.getElementById('code').focus();
        });

        // Enter key submit
        document.getElementById('code').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.closest('form').submit();
            }
        });
    </script>
</body>

</html>

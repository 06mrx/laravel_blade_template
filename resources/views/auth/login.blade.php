<x-guest-layout>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'float': 'float 3s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(10px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        slideUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(30px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0px)'
                            },
                            '50%': {
                                transform: 'translateY(-10px)'
                            }
                        }
                    }
                }
            }
        }
    </script>
    </head>

    <body class="min-h-screen bg-gradient-to-br from-sky-50 via-blue-50 to-sky-100 flex items-center justify-center p-4">
        <!-- Background Decorations -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute -top-40 -right-40 w-80 h-80 bg-sky-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float">
            </div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"
                style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-sky-100 rounded-full mix-blend-multiply filter blur-xl opacity-50 animate-float"
                style="animation-delay: 4s;"></div>
        </div>

        <!-- Login Container -->
        <div class="relative w-full max-w-md">
            <!-- Glass Card Effect -->
            <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 p-6 animate-slide-up">

                <!-- Header -->
                <div class="text-center mb-6 animate-fade-in">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-sky-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h2
                        class="text-2xl font-bold bg-gradient-to-r from-sky-600 to-blue-600 bg-clip-text text-transparent mb-1">
                        Welcome Back</h2>
                    <p class="text-gray-600 text-sm">Sign in to your account to continue</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div
                        class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm animate-fade-in">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{ session('status') }}
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <!-- Email Field -->
                    <div class="space-y-2 animate-fade-in" style="animation-delay: 0.1s;">
                        <label for="email" class="block text-sm font-semibold text-gray-700">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207">
                                    </path>
                                </svg>
                            </div>
                            <input id="email" name="email" type="email" required autocomplete="username"
                                autofocus value="{{ old('email') }}"
                                class="w-full pl-10 pr-4 py-2.5 border @error('email') border-red-300 @else border-gray-200 @enderror rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-sky-500 focus:outline-none transition-all duration-200 bg-white/50 backdrop-blur-sm hover:bg-white/70"
                                placeholder="Enter your email">
                        </div>
                        <div id="email-error" class="text-red-500 text-sm @error('email') block @else hidden @enderror">
                            @error('email')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2 animate-fade-in" style="animation-delay: 0.2s;">
                        <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" required
                                autocomplete="current-password"
                                class="w-full pl-10 pr-12 py-2.5 border @error('password') border-red-300 @else border-gray-200 @enderror rounded-xl focus:ring-2 focus:ring-sky-500 focus:border-sky-500 focus:outline-none transition-all duration-200 bg-white/50 backdrop-blur-sm hover:bg-white/70"
                                placeholder="Enter your password">
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                <svg id="eyeOpen" class="h-5 w-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                <svg id="eyeClosed" class="h-5 w-5 hidden" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        <div id="password-error"
                            class="text-red-500 text-sm @error('password') block @else hidden @enderror">
                            @error('password')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between animate-fade-in" style="animation-delay: 0.3s;">
                        <label class="flex items-center cursor-pointer group">
                            <input id="remember_me" name="remember" type="checkbox"
                                class="w-4 h-4 text-sky-600 bg-white border-gray-300 rounded focus:ring-sky-500 focus:ring-2 transition-all duration-200">
                            <span
                                class="ml-2 text-sm text-gray-600 group-hover:text-gray-800 transition-colors">Remember
                                me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-sky-600 hover:text-sky-800 font-medium transition-colors duration-200 hover:underline">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="loginButton"
                        class="w-full bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white font-semibold py-2.5 px-4 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 animate-fade-in"
                        style="animation-delay: 0.4s;">
                        <span id="loginText">{{ __('Log in') }}</span>
                        <span id="loginLoader" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Signing in...
                        </span>
                    </button>
                </form>

                <!-- Social Login -->
                <div class="mt-6 animate-fade-in" style="animation-delay: 0.5s;">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white/80 text-gray-500">Or continue with</span>
                        </div>
                    </div>

                    <div class="mt-3 grid grid-cols-2 gap-3">
                        <button
                            class="w-full inline-flex justify-center py-2.5 px-4 rounded-xl border border-gray-200 bg-white/50 backdrop-blur-sm text-sm font-medium text-gray-700 hover:bg-white/70 hover:scale-[1.02] transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                                <path fill="#4285F4"
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                <path fill="#34A853"
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                <path fill="#FBBC05"
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                <path fill="#EA4335"
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                            </svg>
                            Google
                        </button>
                        <button
                            class="w-full inline-flex justify-center py-2.5 px-4 rounded-xl border border-gray-200 bg-white/50 backdrop-blur-sm text-sm font-medium text-gray-700 hover:bg-white/70 hover:scale-[1.02] transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.899 2.739.099.120.112.225.083.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z" />
                            </svg>
                            GitHub
                        </button>
                    </div>
                </div>

                <!-- Sign Up Link -->
                <div class="text-center mt-4 animate-fade-in" style="animation-delay: 0.6s;">
                    <p class="text-sm text-gray-600">
                        Don't have an account?
                        <a href="{{ route('register') }}"
                            class="font-medium text-sky-600 hover:text-sky-800 transition-colors duration-200 hover:underline">
                            Sign up here
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </body>
</x-guest-layout>
<script>
    // Password toggle functionality
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            eyeOpen.classList.toggle('hidden');
            eyeClosed.classList.toggle('hidden');
        });
    }

    // Form submission with loading state (optional for better UX)
    const loginForm = document.querySelector('form');
    const loginButton = document.getElementById('loginButton');
    const loginText = document.getElementById('loginText');
    const loginLoader = document.getElementById('loginLoader');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            // Show loading state
            if (loginText && loginLoader) {
                loginText.classList.add('hidden');
                loginLoader.classList.remove('hidden');
                loginButton.disabled = true;
            }
        });
    }

    // // Input focus effects for better UX
    // const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
    // inputs.forEach(input => {
    //     input.addEventListener('focus', function() {
    //         this.parentElement.classList.add('ring-2', 'ring-sky-500');
    //     });

    //     input.addEventListener('blur', function() {
    //         this.parentElement.classList.remove('ring-2', 'ring-sky-500');
    //     });
    // });
</script>
</body>

</html>

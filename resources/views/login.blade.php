<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="h-full">
        <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8" 
             x-data="{
        activeTab: 'loginUser',
        loginError: '',
        registerError: '',
        registerSuccess: false,
        isLoading: false,

        // FUNGSI UNTUK LOGIN KE API BACKEND
        async loginUser() {
            this.isLoading = true;
            this.loginError = '';
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                // Memanggil endpoint /api/login dengan metode POST
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                // Mengambil data JSON dari respons
                const data = await response.json();

                // Jika respons berhasil (status 200 OK)
                if (response.ok) {
                    // Simpan token ke localStorage untuk sesi login
                    localStorage.setItem('authToken', data.token);
                    localStorage.setItem('currentUser', JSON.stringify(data.user));
                    
                    // Arahkan ke halaman utama/dashboard
                    window.location.href = '/index'; // Ganti '/index' jika perlu
                } else {
                    // Jika gagal, tampilkan pesan error dari server
                    this.loginError = data.message || 'Terjadi kesalahan.';
                }
            } catch (error) {
                // Jika ada error jaringan
                this.loginError = 'Tidak dapat terhubung ke server.';
            } finally {
                this.isLoading = false;
            }
        },

        // FUNGSI UNTUK REGISTER KE API BACKEND
        async registerUser() {
            this.isLoading = true;
            this.registerError = '';
            this.registerSuccess = false;

            const name = document.getElementById('name').value;
            const email = document.getElementById('email-register').value;
            const password = document.getElementById('password-register').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (password !== confirmPassword) {
                this.registerError = 'Password tidak cocok';
                this.isLoading = false;
                return;
            }

            try {
                // Memanggil endpoint /api/register dengan metode POST
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ name, email, password })
                });

                const data = await response.json();

                if (response.ok) {
                    // Jika registrasi berhasil, tampilkan pesan sukses
                    this.registerSuccess = true;
                    this.registerError = '';
                    
                    // Reset form
                    document.getElementById('register-form').reset();
                    
                    // Otomatis pindah ke tab login setelah 2 detik
                    setTimeout(() => {
                        this.activeTab = 'login';
                        this.registerSuccess = false;
                    }, 2000);
                } else {
                    // Jika ada error validasi dari server
                    if (data.errors) {
                        this.registerError = Object.values(data.errors).join(' ');
                    } else {
                        this.registerError = data.message || 'Gagal mendaftar.';
                    }
                }
            } catch (error) {
                this.registerError = 'Tidak dapat terhubung ke server.';
            } finally {
                this.isLoading = false;
            }
        }
     }">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <h2 class="mt-6 text-center text-3xl font-extrabold text-black">
                    Welcome
                </h2>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white py-8 px-4 sm:shadow sm:rounded-lg sm:px-10 sm:border sm:border-gray-200">
                    <!-- Tabs -->
                    <div class="flex border-b border-gray-200">
                        <button 
                            @click="activeTab = 'loginUser'"
                            :class="activeTab === 'login' ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="py-4 px-1 border-b-2 font-medium text-sm flex-1 text-center">
                            Sign in
                        </button>
                        <button 
                            @click="activeTab = 'registerUser'"
                            :class="activeTab === 'register' ? 'border-black text-black' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            class="py-4 px-1 border-b-2 font-medium text-sm flex-1 text-center">
                            Create account
                        </button>
                    </div>

                    <!-- Login Form -->
                    <div x-show="activeTab === 'loginUser'" class="space-y-6 pt-6">
                        <form class="space-y-6" @submit.prevent="loginUser">
                            <div x-show="loginError" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                <span x-text="loginError"></span>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    Email address
                                </label>
                                <div class="mt-1">
                                    <input id="email" name="email" type="email" autocomplete="email" required
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                                </div>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    Password
                                </label>
                                <div class="mt-1">
                                    <input id="password" name="password" type="password" autocomplete="current-password" required
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input id="remember-me" name="remember-me" type="checkbox"
                                        class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded">
                                    <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                                        Remember me
                                    </label>
                                </div>

                                <div class="text-sm">
                                    <a href="#" class="font-medium text-black hover:text-gray-700">
                                        Forgot your password?
                                    </a>
                                </div>
                            </div>

                            <div>
                                <button type="submit"
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                                    Sign in
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Register Form -->
                    <div x-show="activeTab === 'registerUser'" class="space-y-6 pt-6">
                        <form id="register-form" class="space-y-6" @submit.prevent="registerUser">
                            <div x-show="registerError" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                <span x-text="registerError"></span>
                            </div>
                            
                            <div x-show="registerSuccess" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                                Pendaftaran berhasil! Anda akan dialihkan ke halaman login.
                            </div>
                            
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    Full name
                                </label>
                                <div class="mt-1">
                                    <input id="name" name="name" type="text" autocomplete="name" required
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                                </div>
                            </div>

                            <div>
                                <label for="email-register" class="block text-sm font-medium text-gray-700">
                                    Email address
                                </label>
                                <div class="mt-1">
                                    <input id="email-register" name="email" type="email" autocomplete="email" required
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                                </div>
                            </div>

                            <div>
                                <label for="password-register" class="block text-sm font-medium text-gray-700">
                                    Password
                                </label>
                                <div class="mt-1">
                                    <input id="password-register" name="password" type="password" autocomplete="new-password" required
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                                </div>
                            </div>

                            <div>
                                <label for="confirm-password" class="block text-sm font-medium text-gray-700">
                                    Confirm Password
                                </label>
                                <div class="mt-1">
                                    <input id="confirm-password" name="confirm-password" type="password" autocomplete="new-password" required
                                        class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                                </div>
                            </div>

                            <div class="flex items-center">
                                <input id="terms" name="terms" type="checkbox" required
                                    class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded">
                                <label for="terms" class="ml-2 block text-sm text-gray-700">
                                    I agree to the <a href="#" class="font-medium text-black hover:text-gray-700">Terms and Conditions</a>
                                </label>
                            </div>

                            <div>
                                <button type="submit"
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                                    Create account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - UMKM Airbrush</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-6 sm:p-8">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-center text-gray-800 mb-6 sm:mb-8">
            Daftar Akun
        </h1>
        
        <form method="POST" action="{{ route('register') }}" class="space-y-4 sm:space-y-5">
            @csrf

            <!-- Nama Lengkap Field -->
            <div>
                <label class="block text-sm sm:text-base text-gray-700 font-semibold mb-2">
                    Nama Lengkap
                </label>
                <input type="text" name="name" value="{{ old('name') }}" 
                    class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('name') border-red-500 @enderror"
                    placeholder="Masukkan nama lengkap" required>
                @error('name')
                    <span class="text-red-500 text-xs sm:text-sm mt-1 inline-block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Username Field -->
            <div>
                <label class="block text-sm sm:text-base text-gray-700 font-semibold mb-2">
                    Username (Unik)
                </label>
                <input type="text" name="username" value="{{ old('username') }}" 
                    class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('username') border-red-500 @enderror"
                    placeholder="Username unik untuk login" required>
                @error('username')
                    <span class="text-red-500 text-xs sm:text-sm mt-1 inline-block">{{ $message }}</span>
                @enderror
            </div>

            <!-- WhatsApp Field -->
            <div>
                <label class="block text-sm sm:text-base text-gray-700 font-semibold mb-2">
                    Nomor WhatsApp Lengkap
                </label>
                <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" 
                    class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('whatsapp') border-red-500 @enderror"
                    placeholder="Nomor WhatsApp untuk komunikasi" required>
                @error('whatsapp')
                    <span class="text-red-500 text-xs sm:text-sm mt-1 inline-block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password Field -->
            <div>
                <label class="block text-sm sm:text-base text-gray-700 font-semibold mb-2">
                    Password
                </label>
                <input type="password" name="password" 
                    class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('password') border-red-500 @enderror"
                    placeholder="Minimal 6 karakter" required>
                @error('password')
                    <span class="text-red-500 text-xs sm:text-sm mt-1 inline-block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password Confirmation Field -->
            <div>
                <label class="block text-sm sm:text-base text-gray-700 font-semibold mb-2">
                    Konfirmasi Password
                </label>
                <input type="password" name="password_confirmation" 
                    class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Ulangi password" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2.5 sm:py-3 text-sm sm:text-base rounded-md transition-colors shadow-md hover:shadow-lg active:scale-95 transform mt-6 sm:mt-8">
                Daftar
            </button>
        </form>

        <!-- Login Link -->
        <p class="text-center text-gray-600 text-sm sm:text-base mt-6 sm:mt-8">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="text-blue-500 font-semibold hover:underline hover:text-blue-600 transition">
                Login di sini
            </a>
        </p>
    </div>
</body>
</html>
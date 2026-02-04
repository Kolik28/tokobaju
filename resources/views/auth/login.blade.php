<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UMKM Airbrush</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-6 sm:p-8">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-center text-gray-800 mb-6 sm:mb-8">
            UMKM Airbrush
        </h1>
        
        <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-6">
            @csrf

            <!-- Username Field -->
            <div>
                <label class="block text-sm sm:text-base text-gray-700 font-semibold mb-2">
                    Username
                </label>
                <input type="text" name="username" value="{{ old('username') }}" 
                    class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('username') border-red-500 @enderror"
                    placeholder="Masukkan username Anda" required>
                @error('username')
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
                    placeholder="Masukkan password Anda" required>
                @error('password')
                    <span class="text-red-500 text-xs sm:text-sm mt-1 inline-block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2.5 sm:py-3 text-sm sm:text-base rounded-md transition-colors shadow-md hover:shadow-lg active:scale-95 transform">
                Login
            </button>
        </form>

        <!-- Register Link -->
        <p class="text-center text-gray-600 text-sm sm:text-base mt-6 sm:mt-8">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="text-blue-500 font-semibold hover:underline hover:text-blue-600 transition">
                Daftar di sini
            </a>
        </p>
    </div>
</body>
</html>
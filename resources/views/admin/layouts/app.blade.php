<html lang="en">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>PC Store</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
      /* Utility Menghilangkan Scrollbar */
      .no-scrollbar::-webkit-scrollbar {
          display: none;
      }

      .no-scrollbar {
          -ms-overflow-style: none;
          scrollbar-width: none;
      }

      .gradient-text {
          background: linear-gradient(to right, hsl(16, 90%, 58%), hsl(210, 100%, 50%));
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
          background-clip: text;
      }
  </style>

  <body class="bg-gray-50">
      <!-- Admin Navbar with Tailwind CSS -->
<nav class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            <!-- Logo & Brand -->
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 group">
                        <div>
                            <h1 class="text-lg font-bold text-gray-900 group-hover:text-orange-600 transition">Admin Panel</h1>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Desktop Navigation (Hidden on mobile) -->
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}">
                    <span class="text-sm font-medium text-gray-700 hover:text-orange-600 transition">
                        Kembali ke Toko
                    </span>
                </a>
                <a href="{{ route('admin.dashboard') }}" 
                    class="text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-orange-600' : 'text-gray-700 hover:text-orange-600' }} transition">
                    Dashboard
                </a>

                <a href="{{ route('admin.orders') }}" 
                    class="text-sm font-medium {{ request()->routeIs('admin.orders') ? 'text-orange-600' : 'text-gray-700 hover:text-orange-600' }} transition">
                    Orders
                </a>

                <a href="{{ route('admin.users') }}" 
                    class="text-sm font-medium {{ request()->routeIs('admin.users') ? 'text-orange-600' : 'text-gray-700 hover:text-orange-600' }} transition">
                    Users
                </a>

                <a href="{{ route('admin.skip-queue.requests') }}" 
                    class="text-sm font-medium {{ request()->routeIs('admin.skip-queue.requests') ? 'text-orange-600' : 'text-gray-700 hover:text-orange-600' }} transition">
                    Approve Queue
                </a>

                <a href="{{ route('admin.dp.list') }}" 
                    class="text-sm font-medium {{ request()->routeIs('admin.dp.list') ? 'text-orange-600' : 'text-gray-700 hover:text-orange-600' }} transition">
                    Approve DP
                </a>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-2 sm:gap-4">

                <!-- User Menu -->
                <div class="relative ml-3">
                    <button 
                        onclick="toggleUserMenu()" 
                        class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition"
                    >
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=random&size=32" 
                            alt="{{ Auth::user()->name }}" 
                            class="w-8 h-8 rounded-full">
                        <span class="text-sm font-medium text-gray-700 hidden sm:inline">{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 text-gray-500 transition-transform duration-200" id="userChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- User Dropdown -->
                    <div id="userDropdown" class="hidden absolute right-0 mt-1 w-56 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">
                        <!-- Profile Info -->
                        <div class="px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=random&size=40" 
                                    alt="{{ Auth::user()->name }}" 
                                    class="w-10 h-10 rounded-full">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Logout Button -->
                        <form action="{{ route('logout') }}" method="POST" class="px-2 py-1">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg transition text-left">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Mobile Menu Toggle -->
                <button 
                    onclick="toggleMobileMenu()" 
                    class="md:hidden p-2 text-gray-700 hover:bg-gray-100 rounded-lg transition"
                >
                    <svg class="w-5 h-5" id="mobileMenuIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden pb-4 space-y-1">
            <a href="{{ route('home') }}" class="block px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-lg transition">
                Kembali ke Toko
            </a>
            <a href="{{ route('admin.dashboard') }}" 
                class="block px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-orange-600 bg-orange-50' : 'text-gray-700 hover:bg-gray-50' }} rounded-lg transition">
                Dashboard
            </a>

            <a href="{{ route('admin.orders') }}" 
                class="block px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.orders') ? 'text-orange-600 bg-orange-50' : 'text-gray-700 hover:bg-gray-50' }} rounded-lg transition">
                Orders
            </a>

            <a href="{{ route('admin.users') }}" 
                class="block px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.users') ? 'text-orange-600 bg-orange-50' : 'text-gray-700 hover:bg-gray-50' }} rounded-lg transition">
                Users
            </a>

            <a href="{{ route('admin.skip-queue.requests') }}" 
                class="block px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.skip-queue.requests') ? 'text-orange-600 bg-orange-50' : 'text-gray-700 hover:bg-gray-50' }} rounded-lg transition">
                Approve Queue
            </a>

            <a href="{{ route('admin.dp.list') }}" 
                class="block px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.dp.list') ? 'text-orange-600 bg-orange-50' : 'text-gray-700 hover:bg-gray-50' }} rounded-lg transition">
                Approve DP
            </a>

            <!-- Mobile Logout -->
            <div class="border-t border-gray-100 mt-2 pt-2">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition text-left">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    /**
     * Toggle User Menu Dropdown
     */
    function toggleUserMenu() {
        const dropdown = document.getElementById('userDropdown');
        const chevron = document.getElementById('userChevron');
        const isOpen = !dropdown.classList.contains('hidden');

        dropdown.classList.toggle('hidden');
        chevron.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
    }

    /**
     * Toggle Mobile Menu
     */
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('hidden');
    }

    /**
     * Close dropdowns when clicking outside
     */
    document.addEventListener('click', function(event) {
        const userMenuContainer = event.target.closest('.relative');
        if (!userMenuContainer) {
            document.getElementById('userDropdown')?.classList.add('hidden');
            const chevron = document.getElementById('userChevron');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        }
    });

    /**
     * Close mobile menu when clicking a nav link
     */
    document.querySelectorAll('#mobileMenu a').forEach(link => {
        link.addEventListener('click', () => {
            document.getElementById('mobileMenu').classList.add('hidden');
        });
    });
</script>

      <!-- Main Content -->
      <main class="container mx-auto px-4 py-8">
          @yield('content')
      </main>

  </body>
  </html>
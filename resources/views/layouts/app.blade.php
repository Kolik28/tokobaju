  <html lang="en">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PC Store</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
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
      <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-lg border-b border-gray-200 shadow-sm">
          <div class="container mx-auto px-4">
              <div class="flex justify-between items-center h-16">
                  <a class="text-2xl font-bold gradient-text">PC Store</a>

                  <!-- Desktop Navigation -->
                  <div class="hidden md:flex items-center gap-2">
                        @if (Auth::user()->isAdmin())
                            <a target="_blank" href="{{ route('admin.dashboard') }}"
                                class="px-4 py-2 rounded-md font-medium
                                    {{ request()->routeIs('admin.dashboard') ? 'bg-orange-500 text-white' : 'hover:bg-gray-100' }}">Admin</a>
                        @endif
                      <a href="{{ route('home') }}"
                          class="px-4 py-2 rounded-md font-medium
                            {{ request()->routeIs('home') || request()->routeIs('') ? 'bg-orange-500 text-white' : 'hover:bg-gray-100' }}">Home</a>
                      <a href="{{ route('orders.index') }}"
                          class="px-4 py-2 rounded-md font-medium
                            {{ request()->routeIs('orders.index') ? 'bg-orange-500 text-white' : 'hover:bg-gray-100' }}">Orders</a>
                      <a href="{{ route('orders.salip') }}"
                          class="px-4 py-2 rounded-md font-medium
                            {{ request()->routeIs('orders.salip') ? 'bg-orange-500 text-white' : 'hover:bg-gray-100' }}">Salip
                          Antrian</a>
                      <a href="{{ route('orders.dp') }}"
                          class="px-4 py-2 rounded-md font-medium
                            {{ request()->routeIs('orders.dp') ? 'bg-orange-500 text-white' : 'hover:bg-gray-100' }}">DP</a>
                      <a href="{{ route('about') }}"
                          class="px-4 py-2 rounded-md font-medium
                            {{ request()->routeIs('about') ? 'bg-orange-500 text-white' : 'hover:bg-gray-100' }}">About</a>
                      <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 rounded-md hover:bg-gray-100 font-medium">Logout</button>
                      </form>
                  </div>

                  <!-- Mobile Menu Button -->
                  <button class="md:hidden p-2" onclick="toggleMenu()">
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"></path>
                      </svg>
                  </button>
              </div>

              <!-- Mobile Navigation -->
              <div id="mobileMenu" class="hidden md:hidden py-4 border-t border-gray-200">
                  <div class="flex flex-col gap-2">
                        @if (Auth::user()->isAdmin())
                            <a target="_blank" href="{{ route('admin.dashboard') }}"
                                class="block px-4 py-2 rounded-md font-medium
                                    {{ request()->routeIs('admin.dashboard') ? 'bg-orange-500 text-white' : 'hover:bg-gray-100' }}">Admin</a>
                        @endif
                      <a href="{{ route('home') }}"
                          class="px-4 py-2 rounded-md font-medium
                            {{ request()->routeIs('home') || request()->routeIs('') ? 'bg-orange-500 text-white' : 'hover:bg-gray-100' }}">Home</a>
                      <a href="{{ route('orders.index') }}"
                          class="px-4 py-2 rounded-md font-medium
                            {{ request()->routeIs('orders.index') ? 'bg-orange-500 text-white' : 'hover:bg-gray-100' }}">Orders</a>
                      <a href="{{ route('orders.salip') }}"
                          class="px-4 py-2 rounded-md font-medium
                            {{ request()->routeIs('orders.salip') ? 'bg-orange-500 text-white' : 'hover:bg-gray-100' }}">Salip
                          Antrian</a>
                      <a href="{{ route('orders.dp') }}"
                          class="px-4 py-2 rounded-md font-medium
                            {{ request()->routeIs('orders.dp') ? 'bg-orange-500 text-white' : 'hover:bg-gray-100' }}">DP</a>
                      <a href="{{ route('about') }}"
                          class="px-4 py-2 rounded-md font-medium
                            {{ request()->routeIs('about') ? 'bg-orange-500 text-white' : 'hover:bg-gray-100' }}">About</a>
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 rounded-md hover:bg-gray-100 font-medium">Logout</button>
                        </form>
                  </div>
              </div>
          </div>
      </nav>

      <!-- Main Content -->
      <main class="container mx-auto px-4 py-8">
          @yield('content')
      </main>

  </body>
  <script>
      function toggleMenu() {
          const menu = document.getElementById('mobileMenu');
          menu.classList.toggle('hidden');
      }
  </script>

  </html>

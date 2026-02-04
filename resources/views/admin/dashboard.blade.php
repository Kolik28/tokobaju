@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div id="dashboard" class="page active">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Card Total Pengguna -->
        <div class="p-4 bg-white rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-primary flex-shrink-0">
                    <i class="fas fa-users text-lg"></i>
                </div>
                <div class="ml-4 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total Pengguna</p>
                    <p class="text-xl sm:text-2xl font-semibold">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
        
        <!-- Card Total Pesanan -->
        <div class="p-4 bg-white rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-500 flex-shrink-0">
                    <i class="fas fa-shopping-cart text-lg"></i>
                </div>
                <div class="ml-4 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total Pesanan</p>
                    <p class="text-xl sm:text-2xl font-semibold">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>
        
        <!-- Card Pendapatan -->
        <div class="p-4 bg-white rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-500 flex-shrink-0">
                    <i class="fas fa-dollar-sign text-lg"></i>
                </div>
                <div class="ml-4 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Jumlah Salip Queue</p>
                    <p class="text-xl sm:text-2xl font-semibold">{{ $pendingSkipCount }}</p>
                </div>
            </div>
        </div>
        
        <!-- Card Pertumbuhan -->
        <div class="p-4 bg-white rounded-lg shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 flex-shrink-0">
                    <i class="fas fa-chart-line text-lg"></i>
                </div>
                <div class="ml-4 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Jumlah DP</p>
                    <p class="text-xl sm:text-2xl font-semibold">{{ $pendingDpCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart & Activity Section -->
    <div class="grid grid-cols-1 gap-4 sm:gap-6 lg:grid-cols-2">
        <!-- Statistik Pengunjung -->
        <div class="p-4 bg-white rounded-lg shadow">
            <h3 class="mb-4 text-base sm:text-lg font-semibold">Statistik Pengunjung</h3>
            <div class="h-48 sm:h-64 bg-gray-100 rounded flex items-center justify-center">
                <p class="text-gray-500 text-sm sm:text-base">Grafik akan ditampilkan di sini</p>
            </div>
        </div>
        
        <!-- Aktivitas Terbaru -->
        <div class="p-4 bg-white rounded-lg shadow">
            <h3 class="mb-4 text-base sm:text-lg font-semibold">Aktivitas Terbaru</h3>
            <div class="space-y-3 sm:space-y-4">
                <!-- Item 1 -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center text-primary">
                        <i class="fas fa-user-plus text-xs sm:text-sm"></i>
                    </div>
                    <div class="ml-3 min-w-0">
                        <p class="text-xs sm:text-sm font-medium truncate">Pengguna baru terdaftar</p>
                        <p class="text-xs text-gray-500">2 menit yang lalu</p>
                    </div>
                </div>
                
                <!-- Item 2 -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-500">
                        <i class="fas fa-shopping-cart text-xs sm:text-sm"></i>
                    </div>
                    <div class="ml-3 min-w-0">
                        <p class="text-xs sm:text-sm font-medium truncate">Pesanan baru diterima</p>
                        <p class="text-xs text-gray-500">15 menit yang lalu</p>
                    </div>
                </div>
                
                <!-- Item 3 -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center text-purple-500">
                        <i class="fas fa-chart-bar text-xs sm:text-sm"></i>
                    </div>
                    <div class="ml-3 min-w-0">
                        <p class="text-xs sm:text-sm font-medium truncate">Laporan bulanan dihasilkan</p>
                        <p class="text-xs text-gray-500">1 jam yang lalu</p>
                    </div>
                </div>
                
                <!-- Item 4 -->
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-500">
                        <i class="fas fa-exclamation-triangle text-xs sm:text-sm"></i>
                    </div>
                    <div class="ml-3 min-w-0">
                        <p class="text-xs sm:text-sm font-medium truncate">Peringatan stok rendah</p>
                        <p class="text-xs text-gray-500">2 jam yang lalu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Pesanan Terbaru -->
    <div class="mt-4 sm:mt-6 bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h3 class="text-base sm:text-lg font-semibold">Pesanan Terbaru</h3>
        </div>
        
        <!-- Tampilan Desktop (Table) -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ID Pesanan</th>
                        <th class="px-4 sm:px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Pelanggan</th>
                        <th class="px-4 sm:px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tanggal</th>
                        <th class="px-4 sm:px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Jumlah</th>
                        <th class="px-4 sm:px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">#ORD-001</td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">John Doe</td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">12 Mei 2023</td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">$245.99</td>
                        <td class="px-4 sm:px-6 py-4">
                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">Selesai</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">#ORD-002</td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">Jane Smith</td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">13 Mei 2023</td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">$128.50</td>
                        <td class="px-4 sm:px-6 py-4">
                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">Pending</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">#ORD-003</td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">Robert Johnson</td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">14 Mei 2023</td>
                        <td class="px-4 sm:px-6 py-4 text-sm text-gray-900">$89.99</td>
                        <td class="px-4 sm:px-6 py-4">
                            <span class="inline-flex px-2 text-xs font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full">Diproses</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Tampilan Mobile (Card) -->
        <div class="sm:hidden divide-y divide-gray-200">
            <!-- Card 1 -->
            <div class="p-4 hover:bg-gray-50">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-sm font-medium text-gray-900">#ORD-001</span>
                    <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">Selesai</span>
                </div>
                <div class="space-y-1">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Pelanggan</span>
                        <span class="text-xs text-gray-900">John Doe</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Tanggal</span>
                        <span class="text-xs text-gray-900">12 Mei 2023</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Jumlah</span>
                        <span class="text-xs font-semibold text-gray-900">$245.99</span>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="p-4 hover:bg-gray-50">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-sm font-medium text-gray-900">#ORD-002</span>
                    <span class="inline-flex px-2 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">Pending</span>
                </div>
                <div class="space-y-1">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Pelanggan</span>
                        <span class="text-xs text-gray-900">Jane Smith</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Tanggal</span>
                        <span class="text-xs text-gray-900">13 Mei 2023</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Jumlah</span>
                        <span class="text-xs font-semibold text-gray-900">$128.50</span>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="p-4 hover:bg-gray-50">
                <div class="flex justify-between items-start mb-2">
                    <span class="text-sm font-medium text-gray-900">#ORD-003</span>
                    <span class="inline-flex px-2 text-xs font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full">Diproses</span>
                </div>
                <div class="space-y-1">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Pelanggan</span>
                        <span class="text-xs text-gray-900">Robert Johnson</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Tanggal</span>
                        <span class="text-xs text-gray-900">14 Mei 2023</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500">Jumlah</span>
                        <span class="text-xs font-semibold text-gray-900">$89.99</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
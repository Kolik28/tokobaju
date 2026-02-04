@extends('layouts.app')

@section('content')
    <div class="w-full px-3 sm:px-4 py-4 sm:py-6">
        <div class="max-w-4xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-8 sm:mb-12">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2 sm:mb-3 gradient-text capitalize">
                    Selamat Datang, {{ $user->name }}!
                </h1>
                <p class="text-sm sm:text-base lg:text-lg text-gray-600">
                    Kelola pesanan dan antrian Anda dengan mudah
                </p>
            </div>

            <!-- User Info Card -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg shadow-md p-4 sm:p-6 mb-6 sm:mb-8">
                <h2 class="text-lg sm:text-xl font-bold text-blue-900 mb-4">Informasi Profil</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600">Nama</p>
                        <p class="text-base sm:text-lg font-semibold text-blue-900 capitalize">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600">No. WhatsApp</p>
                        <p class="text-base sm:text-lg font-semibold text-blue-900">{{ $user->whatsapp ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Order Selection -->
            @if ($userOrders->isNotEmpty())
                <form method="GET" action="{{ route('home') }}" id="orderForm">
                    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6 sm:mb-8">
                        <label for="orders" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-3">
                            Pilih Pesanan Anda
                        </label>
                        <select name="order_id" id="orders"
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            onchange="this.form.submit()">
                            @foreach ($userOrders as $order)
                                <option value="{{ $order->id }}"
                                    {{ $selectedOrder && $selectedOrder->id == $order->id ? 'selected' : '' }}>
                                    Order #{{ $order->id }} - {{ ucfirst($order->category) }} - Posisi:
                                    #{{ $order->queue_position }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            @endif

            <!-- Current Queue Status -->
            @if ($selectedOrder)
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6 sm:mb-8">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4">Status Antrian Anda</h2>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                        <div class="bg-orange-50 p-3 sm:p-4 rounded-lg">
                            <p class="text-xs sm:text-sm text-gray-600">Posisi Antrian</p>
                            <p class="text-2xl sm:text-3xl font-bold text-orange-600">#{{ $selectedOrder->queue_position }}
                            </p>
                        </div>
                        <div class="bg-blue-50 p-3 sm:p-4 rounded-lg">
                            <p class="text-xs sm:text-sm text-gray-600">Order ID</p>
                            <p class="text-2xl sm:text-3xl font-bold text-blue-600">#{{ $selectedOrder->id }}</p>
                        </div>
                        <div class="bg-green-50 p-3 sm:p-4 rounded-lg">
                            <p class="text-xs sm:text-sm text-gray-600">Kategori</p>
                            <p class="text-xs sm:text-sm font-bold text-green-600 uppercase">{{ $selectedOrder->category }}
                            </p>
                        </div>
                        <div class="bg-purple-50 p-3 sm:p-4 rounded-lg">
                            <p class="text-xs sm:text-sm text-gray-600">Status</p>
                            <p class="text-xs sm:text-sm font-bold text-purple-600 uppercase">
                                {{ $selectedOrder->queue_status }}</p>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="font-semibold text-base sm:text-lg text-gray-800 mb-4">Detail Pesanan</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <p class="text-xs sm:text-sm text-gray-600">Ukuran</p>
                                <p class="text-base sm:text-lg font-semibold text-gray-800">{{ $selectedOrder->size }} cm
                                </p>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-600">Varian</p>
                                <p class="text-base sm:text-lg font-semibold text-gray-800">
                                    {{ $selectedOrder->variant ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-600">Harga Total</p>
                                <p class="text-base sm:text-lg font-semibold text-green-600">Rp
                                    {{ number_format($selectedOrder->total_price, 0, ',', '.') }}</p>
                            </div>
                            @if ($selectedOrder->dp_status === 'approved')
                                <div>
                                    <p class="text-xs sm:text-sm text-gray-600">DP yang Sudah Dibayar</p>
                                    <p class="text-base sm:text-lg font-semibold text-orange-600">Rp
                                        {{ number_format($selectedOrder->dp_amount ?? 0, 0, ',', '.') }}</p>
                                </div>
                            @endif
                            <div>
                                <p class="text-xs sm:text-sm text-gray-600">Sisa yang Harus Dibayar</p>
                                <p class="text-base sm:text-lg font-bold text-red-600">Rp
                                    {{ number_format($selectedOrder->remaining_price ?? $selectedOrder->total_price - ($selectedOrder->dp_amount ?? 0), 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-600">Status DP</p>
                                <p class="text-xs sm:text-sm font-semibold text-gray-800">
                                    @if ($selectedOrder->dp_status === 'approved')
                                        <span
                                            class="bg-green-100 text-green-800 px-2 sm:px-3 py-1 rounded-full inline-block text-xs">
                                            Disetujui</span>
                                    @elseif($selectedOrder->dp_status === 'pending')
                                        <span
                                            class="bg-yellow-100 text-yellow-800 px-2 sm:px-3 py-1 rounded-full inline-block text-xs">
                                            Menunggu</span>
                                    @elseif($selectedOrder->dp_status === 'orders')
                                        <span
                                            class="bg-grey-100 text-grey-800 px-2 sm:px-3 py-1 rounded-full inline-block text-xs">
                                            Orders</span>
                                    @else
                                        <span
                                            class="bg-red-100 text-red-800 px-2 sm:px-3 py-1 rounded-full inline-block text-xs">
                                            Ditolak</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 sm:p-6 rounded-lg mb-6 sm:mb-8">
                    <p class="text-xs sm:text-sm text-yellow-800 font-semibold">
                        Anda belum memiliki pesanan aktif. Silakan buat pesanan baru untuk bergabung dalam antrian.
                    </p>
                </div>
            @endif

            <!-- Upcoming Queue List -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6">Antrian Berikutnya (5 Teratas)</h2>

                @if ($queues->isNotEmpty())
                    <!-- Mobile View (Card) -->
                    <div class="sm:hidden space-y-3">
                        @foreach ($queues as $index => $queue)
                            <div
                                class="p-4 rounded-lg border border-gray-200 {{ $selectedOrder && $queue->id === $selectedOrder->id ? 'bg-orange-100 border-orange-400' : 'bg-white' }}">
                                <div class="flex items-center justify-between mb-2">
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 bg-orange-200 text-orange-800 rounded-full font-bold text-sm">
                                        {{ $queue->queue_position }}
                                    </span>
                                    <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                        {{ ucfirst($queue->category) }}
                                    </span>
                                </div>
                                <p class="text-sm font-semibold text-blue-600 mb-1">#{{ $queue->id }}</p>
                                <p class="text-sm font-medium text-gray-800 capitalize">{{ $queue->user->name ?? '-' }}</p>
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop View (Table) -->
                    <div class="hidden sm:block overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 border-b-2 border-gray-300">
                                <tr>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">
                                        No.</th>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">
                                        Order ID</th>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">
                                        Customer</th>
                                    <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm font-semibold text-gray-700">
                                        Kategori</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($queues as $index => $queue)
                                    <tr
                                        class="transition-colors {{ $selectedOrder && $queue->id === $selectedOrder->id ? 'bg-orange-200' : '' }}">
                                        <td class="px-3 sm:px-4 py-3">
                                            <span
                                                class="inline-flex items-center justify-center w-8 h-8 bg-orange-200 text-orange-800 rounded-full font-bold text-sm">
                                                {{ $queue->queue_position }}
                                            </span>
                                        </td>
                                        <td class="px-3 sm:px-4 py-3">
                                            <span class="font-semibold text-blue-600">#{{ $queue->id }}</span>
                                        </td>
                                        <td class="px-3 sm:px-4 py-3">
                                            <span
                                                class="font-medium text-gray-800 capitalize text-sm">{{ $queue->user->name ?? '-' }}</span>
                                        </td>
                                        <td class="px-3 sm:px-4 py-3">
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                                {{ ucfirst($queue->category) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-gray-600 py-8 text-sm sm:text-base">Tidak ada antrian saat ini</p>
                @endif
            </div>
        </div>
    </div>
@endsection

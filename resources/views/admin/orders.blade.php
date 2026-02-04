@extends('admin.layouts.app')

@section('title', 'Manajemen Pesanan')

@section('content')
    <div id="orders" class="container mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6">
        <!-- Header -->
        <div class="mb-4 sm:mb-6">
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800">Manajemen Pesanan</h2>
            <p class="text-sm sm:text-base text-gray-600 mt-1">Kelola dan monitor pesanan pelanggan</p>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div
                class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="text-sm sm:text-base">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.style.display='none'"
                    class="text-green-700 hover:text-green-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span class="text-sm sm:text-base">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.style.display='none'"
                    class="text-red-700 hover:text-red-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Filter Tabs -->
        <div class="mb-4 sm:mb-6 border-b border-gray-200 overflow-x-auto">
            <div class="flex space-x-2 sm:space-x-8 min-w-max sm:min-w-0">
                <button
                    class="filter-tab active px-3 sm:px-4 py-2 border-b-2 border-blue-600 text-blue-600 font-medium whitespace-nowrap text-sm sm:text-base"
                    onclick="filterOrders('all')">
                    Semua <span
                        class="bg-blue-600 text-white rounded-full px-2 py-1 text-xs ml-1 sm:ml-2">{{ $orders->count() }}</span>
                </button>
                <button
                    class="filter-tab px-3 sm:px-4 py-2 border-b-2 border-transparent text-gray-600 font-medium hover:text-gray-800 whitespace-nowrap text-sm sm:text-base"
                    onclick="filterOrders('waiting')">
                    Menunggu <span
                        class="bg-yellow-600 text-white rounded-full px-2 py-1 text-xs ml-1 sm:ml-2">{{ $orders->where('queue_status', 'waiting')->count() }}</span>
                </button>
                <button
                    class="filter-tab px-3 sm:px-4 py-2 border-b-2 border-transparent text-gray-600 font-medium hover:text-gray-800 whitespace-nowrap text-sm sm:text-base"
                    onclick="filterOrders('processing')">
                    Diproses <span
                        class="bg-blue-600 text-white rounded-full px-2 py-1 text-xs ml-1 sm:ml-2">{{ $orders->where('queue_status', 'processing')->count() }}</span>
                </button>
                <button
                    class="filter-tab px-3 sm:px-4 py-2 border-b-2 border-transparent text-gray-600 font-medium hover:text-gray-800 whitespace-nowrap text-sm sm:text-base"
                    onclick="filterOrders('done')">
                    Selesai <span
                        class="bg-green-600 text-white rounded-full px-2 py-1 text-xs ml-1 sm:ml-2">{{ $orders->where('queue_status', 'done')->count() }}</span>
                </button>
            </div>
        </div>

        <!-- Search & Export -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6 gap-3">
            <div class="w-full sm:w-auto flex-1 sm:max-w-md">
                <div class="relative">
                    <input type="text" id="searchOrder" placeholder="Cari order ID, nama customer, kategori..."
                        class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition"
                        onkeyup="searchOrders()">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
            <div class="flex gap-2">
                <button onclick="exportOrders()"
                    class="flex-1 sm:flex-none bg-green-600 hover:bg-green-700 text-white px-4 sm:px-6 py-2.5 rounded-lg flex items-center justify-center gap-2 text-sm sm:text-base font-medium transition shadow-sm hover:shadow-md">
                    <i class="fas fa-file-excel"></i>
                    <span>Export</span>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Tampilan Desktop (Table) -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Order</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Antrian</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="orderTableBody">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-gray-50 transition order-row" data-status="{{ $order->queue_status }}" id="row-{{ $order->id }}">
                                <!-- Order Info -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if ($order->image_path)
                                                <img src="{{ Storage::url($order->image_path) }}" alt="Order Image"
                                                    class="h-10 w-10 rounded-lg object-cover border-2 border-gray-200">
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-xs">
                                                    #{{ $order->id }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900 order-id">
                                                #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $order->created_at->format('d M Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Customer -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 order-customer">
                                        {{ $order->user->name }}
                                    </div>
                                    <div class="flex items-center text-xs text-gray-500 mt-1">
                                        <i class="fab fa-whatsapp text-green-500 mr-1"></i>
                                        <span class="order-whatsapp">{{ $order->user->whatsapp }}</span>
                                    </div>
                                </td>

                                <!-- Product -->
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 capitalize order-category">
                                        {{ $order->category }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Ukuran: {{ $order->size }}
                                        @if ($order->variant)
                                            <span class="ml-1">• {{ $order->variant }}</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Price -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        @if ($order->dp_amount > 0)
                                            <span class="text-green-600 font-medium">DP: Rp
                                                {{ number_format($order->dp_amount, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                    @if ($order->remaining_price > 0)
                                        <div class="text-xs text-orange-600 font-medium">
                                            Sisa: Rp {{ number_format($order->remaining_price, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Queue -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-blue-600">
                                                {{ $order->queue_position }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                dari {{ $order->queue_number }}
                                            </div>
                                        </div>
                                        @if ($order->is_priority)
                                            <span
                                                class="inline-flex items-center px-2 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-300">
                                                <i class="fas fa-bolt mr-1"></i>
                                                Priority
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-y-1" id="status-badge-{{ $order->id }}">
                                        <!-- Queue Status -->
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                                        {{ $order->queue_status === 'waiting'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : ($order->queue_status === 'processing'
                                                ? 'bg-blue-100 text-blue-800'
                                                : ($order->queue_status === 'done'
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-red-100 text-red-800')) }}">
                                            <i
                                                class="fas fa-{{ $order->queue_status === 'waiting'
                                                    ? 'clock'
                                                    : ($order->queue_status === 'processing'
                                                        ? 'spinner'
                                                        : ($order->queue_status === 'done'
                                                            ? 'check-circle'
                                                            : 'times-circle')) }} mr-1"></i>
                                            {{ ucfirst($order->queue_status) }}
                                        </span>

                                        <!-- DP Status -->
                                        @if ($order->dp_status !== 'orders')
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full
                                            {{ $order->dp_status === 'pending'
                                                ? 'bg-yellow-100 text-yellow-700'
                                                : ($order->dp_status === 'approved'
                                                    ? 'bg-green-100 text-green-700'
                                                    : 'bg-red-100 text-red-700') }}">
                                                DP: {{ ucfirst($order->dp_status) }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2" id="actions-{{ $order->id }}">
                                        <!-- Tombol Status: Diproses / Selesai -->
                                        @if ($order->queue_status === 'waiting')
                                            <button onclick="updateOrderStatus({{ $order->id }}, 'processing')"
                                                class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition shadow-sm hover:shadow">
                                                <i class="fas fa-spinner"></i>
                                                <span>Diproses</span>
                                            </button>
                                        @elseif ($order->queue_status === 'processing')
                                            <button onclick="updateOrderStatus({{ $order->id }}, 'done')"
                                                class="inline-flex items-center gap-1 bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition shadow-sm hover:shadow">
                                                <i class="fas fa-check-circle"></i>
                                                <span>Selesai</span>
                                            </button>
                                        @endif

                                        <!-- View Detail -->
                                        <button onclick="viewOrderDetail({{ $order->id }})"
                                            class="inline-flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition shadow-sm hover:shadow">
                                            <i class="fas fa-eye"></i>
                                            <span>Detail</span>
                                        </button>

                                        <!-- WhatsApp -->
                                        <a target="_blank" rel="noopener noreferrer"
                                            href="https://wa.me/{{ $order->user->whatsapp }}"
                                            class="inline-flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition shadow-sm hover:shadow">
                                            Chat
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('admin.orders.delete', $order->id) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Yakin ingin menghapus pesanan #{{ $order->id }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition shadow-sm hover:shadow">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fas fa-shopping-cart text-5xl mb-4"></i>
                                        <p class="text-gray-500 font-medium">Tidak ada pesanan</p>
                                        <p class="text-sm text-gray-400 mt-1">Pesanan baru akan muncul di sini</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Tampilan Tablet (Medium Devices) -->
            <div class="hidden md:block lg:hidden">
                <div class="divide-y divide-gray-200" id="orderTabletList">
                    @forelse ($orders as $order)
                        <div class="p-4 hover:bg-gray-50 transition order-row" data-status="{{ $order->queue_status }}" id="row-{{ $order->id }}">
                            <div class="flex items-start gap-4 mb-3">
                                <!-- Image/Icon -->
                                <div class="flex-shrink-0">
                                    @if ($order->image_path)
                                        <img src="{{ Storage::url($order->image_path) }}" alt="Order"
                                            class="h-16 w-16 rounded-lg object-cover border-2 border-gray-200">
                                    @else
                                        <div
                                            class="h-16 w-16 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold">
                                            #{{ $order->id }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h3 class="text-base font-bold text-gray-900 order-id">
                                                Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                            </h3>
                                            <p class="text-sm text-gray-600 order-customer">{{ $order->user->name }}</p>
                                        </div>
                                        <span id="status-badge-{{ $order->id }}"
                                            class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full
                                        {{ $order->queue_status === 'waiting'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : ($order->queue_status === 'processing'
                                                ? 'bg-blue-100 text-blue-800'
                                                : ($order->queue_status === 'done'
                                                    ? 'bg-green-100 text-green-800'
                                                    : 'bg-red-100 text-red-800')) }}">
                                            {{ ucfirst($order->queue_status) }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                                        <div>
                                            <span class="text-gray-500">Produk:</span>
                                            <span
                                                class="font-medium text-gray-900 capitalize order-category">{{ $order->category }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Antrian:</span>
                                            <span
                                                class="font-bold text-blue-600">{{ $order->queue_position }}/{{ $order->queue_number }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">Total:</span>
                                            <span class="font-bold text-gray-900">Rp
                                                {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-500">DP:</span>
                                            <span class="font-medium text-green-600">Rp
                                                {{ number_format($order->dp_amount, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <div class="flex gap-2 flex-wrap" id="actions-{{ $order->id }}">
                                        <!-- Tombol Status: Diproses / Selesai -->
                                        @if ($order->queue_status === 'waiting')
                                            <button onclick="updateOrderStatus({{ $order->id }}, 'processing')"
                                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg text-sm font-medium transition">
                                                <i class="fas fa-spinner mr-1"></i> Diproses
                                            </button>
                                        @elseif ($order->queue_status === 'processing')
                                            <button onclick="updateOrderStatus({{ $order->id }}, 'done')"
                                                class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg text-sm font-medium transition">
                                                <i class="fas fa-check-circle mr-1"></i> Selesai
                                            </button>
                                        @endif

                                        <button onclick="viewOrderDetail({{ $order->id }})"
                                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-sm font-medium transition">
                                            <i class="fas fa-eye mr-1"></i> Detail
                                        </button>
                                        <a href="https://wa.me/{{ $order->user->whatsapp }}" target="_blank"
                                            class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg text-sm font-medium transition text-center">
                                            <i class="fab fa-whatsapp mr-1"></i> Chat
                                        </a>
                                        <form action="{{ route('admin.orders.delete', $order->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus pesanan #{{ $order->id }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 active:bg-red-800 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <i class="fas fa-shopping-cart text-5xl mb-4 text-gray-300"></i>
                            <p class="text-gray-500 font-medium">Tidak ada pesanan</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tampilan Mobile (Cards) -->
            <div class="md:hidden divide-y divide-gray-200" id="orderMobileList">
                @forelse ($orders as $order)
                    <div class="p-4 hover:bg-gray-50 transition order-row" data-status="{{ $order->queue_status }}" id="row-{{ $order->id }}">
                        <!-- Header -->
                        <div class="flex items-center gap-3 mb-3">
                            @if ($order->image_path)
                                <img src="{{ Storage::url($order->image_path) }}" alt="Order"
                                    class="h-14 w-14 rounded-lg object-cover border-2 border-gray-200">
                            @else
                                <div
                                    class="h-14 w-14 rounded-lg bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm">
                                    #{{ $order->id }}
                                </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-bold text-gray-900 truncate order-id">
                                    Order #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                </h3>
                                <p class="text-xs text-gray-600 truncate order-customer">{{ $order->user->name }}</p>
                                <div class="flex items-center gap-2 mt-1" id="status-badge-{{ $order->id }}">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full
                                    {{ $order->queue_status === 'waiting'
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : ($order->queue_status === 'processing'
                                            ? 'bg-blue-100 text-blue-800'
                                            : ($order->queue_status === 'done'
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-red-100 text-red-800')) }}">
                                        {{ ucfirst($order->queue_status) }}
                                    </span>
                                    @if ($order->is_priority)
                                        <span
                                            class="inline-flex items-center px-1.5 py-0.5 text-xs font-bold rounded bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-bolt"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Info Grid -->
                        <div class="bg-gray-50 rounded-lg p-3 mb-3 space-y-2 text-xs">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Produk:</span>
                                <span class="font-medium text-gray-900 capitalize order-category">{{ $order->category }}
                                    ({{ $order->size }})</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Antrian:</span>
                                <span class="font-bold text-blue-600">Posisi {{ $order->queue_position }} dari
                                    {{ $order->queue_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Harga:</span>
                                <span class="font-bold text-gray-900">Rp
                                    {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">DP Dibayar:</span>
                                <span class="font-medium text-green-600">Rp
                                    {{ number_format($order->dp_amount, 0, ',', '.') }}</span>
                            </div>
                            @if ($order->remaining_price > 0)
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Sisa Bayar:</span>
                                    <span class="font-medium text-orange-600">Rp
                                        {{ number_format($order->remaining_price, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-gray-600">
                                    <i class="fab fa-whatsapp text-green-500 mr-1"></i> WhatsApp:
                                </span>
                                <span class="font-medium text-gray-900 order-whatsapp">{{ $order->user->whatsapp }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2 flex-wrap" id="actions-{{ $order->id }}">
                            <!-- Tombol Status: Diproses / Selesai -->
                            @if ($order->queue_status === 'waiting')
                                <button onclick="updateOrderStatus({{ $order->id }}, 'processing')"
                                    class="flex-1 bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                                    <i class="fas fa-spinner mr-1"></i> Diproses
                                </button>
                            @elseif ($order->queue_status === 'processing')
                                <button onclick="updateOrderStatus({{ $order->id }}, 'done')"
                                    class="flex-1 bg-green-500 hover:bg-green-600 active:bg-green-700 text-white py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                                    <i class="fas fa-check-circle mr-1"></i> Selesai
                                </button>
                            @endif

                            <button onclick="viewOrderDetail({{ $order->id }})"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                                <i class="fas fa-eye mr-1"></i> Detail
                            </button>
                            <a href="https://wa.me/{{ $order->user->whatsapp }}" target="_blank"
                                class="flex-1 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white py-2.5 rounded-lg text-sm font-medium transition shadow-sm text-center">
                                <i class="fab fa-whatsapp mr-1"></i> Chat
                            </a>
                            <form action="{{ route('admin.orders.delete', $order->id) }}" method="POST"
                                onsubmit="return confirm('Hapus pesanan #{{ $order->id }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 active:bg-red-800 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <i class="fas fa-shopping-cart text-4xl mb-3 text-gray-300"></i>
                        <p class="text-gray-500 font-medium text-sm">Tidak ada pesanan</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if (method_exists($orders, 'links'))
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Detail Order -->
    <div id="orderDetailModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-3 sm:p-4 md:p-6 overflow-y-auto">
        <div class="bg-white rounded-lg w-full max-w-4xl my-auto shadow-2xl flex flex-col max-h-[95vh]">
            <!-- Header Modal - Sticky -->
            <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-700 px-4 sm:px-6 md:px-8 py-3 sm:py-4 flex items-center justify-between z-10 rounded-t-lg shadow-md">
                <div class="flex-1">
                    <h3 class="text-lg sm:text-xl font-bold text-white">Detail Pesanan</h3>
                </div>
                <button onclick="closeOrderDetail()" class="text-white hover:bg-blue-800 p-2 rounded-lg transition duration-200 ml-2">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Scrollable Content Area -->
            <div id="orderDetailContent" class="overflow-y-auto flex-1 p-4 sm:p-6 md:p-8">
                <!-- Loading state -->
                <div class="flex items-center justify-center py-8">
                    <div class="text-center">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-3"></div>
                        <p class="text-gray-600">Memuat detail pesanan...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toastContainer" class="fixed top-4 right-4 z-[100] flex flex-col gap-2 pointer-events-none"></div>

    <script>
        // =============================================
        // FUNGSI UPDATE STATUS PESANAN (BARU)
        // =============================================
        function updateOrderStatus(orderId, newStatus) {
            // Konfirmasi dari user
            const confirmMsg = newStatus === 'processing'
                ? 'Ubah status pesanan #' + String(orderId).padStart(5, '0') + ' menjadi Diproses?'
                : 'Tandai pesanan #' + String(orderId).padStart(5, '0') + ' sebagai Selesai?';

            if (!confirm(confirmMsg)) return;

            // Disable semua tombol di row ini saat request berjalan
            const actionsContainer = document.getElementById('actions-' + orderId);
            const buttons = actionsContainer ? actionsContainer.querySelectorAll('button') : [];
            buttons.forEach(btn => {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            });

            // Kirim AJAX request
            fetch(`/admin/orders/${orderId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ queue_status: newStatus })
            })
            .then(response => {
                if (!response.ok) throw new Error('Server error: ' + response.status);
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update badge status di semua tampilan (desktop, tablet, mobile)
                    updateStatusBadge(orderId, newStatus);

                    // Update tombol aksi
                    updateActionButtons(orderId, newStatus);

                    // Update data-status di row
                    const rows = document.querySelectorAll('#row-' + orderId);
                    rows.forEach(row => row.setAttribute('data-status', newStatus));

                    // Tampilkan toast sukses
                    showToast(
                        newStatus === 'processing'
                            ? 'Pesanan #' + String(orderId).padStart(5, '0') + ' sekarang Diproses'
                            : 'Pesanan #' + String(orderId).padStart(5, '0') + ' telah Selesai',
                        'success'
                    );
                } else {
                    throw new Error(data.message || 'Update gagal');
                }
            })
            .catch(error => {
                console.error('Error updating status:', error);
                showToast('Gagal mengubah status: ' + error.message, 'error');

                // Re-enable tombol
                buttons.forEach(btn => {
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                });
            });
        }

        // Update badge status di semua tampilan
        function updateStatusBadge(orderId, newStatus) {
            const statusColors = {
                waiting:    { bg: 'bg-yellow-100', text: 'text-yellow-800', icon: 'fa-clock',         label: 'Waiting' },
                processing: { bg: 'bg-blue-100',   text: 'text-blue-800',   icon: 'fa-spinner',       label: 'Processing' },
                done:       { bg: 'bg-green-100',  text: 'text-green-800',  icon: 'fa-check-circle',  label: 'Done' },
                cancelled:  { bg: 'bg-red-100',    text: 'text-red-800',    icon: 'fa-times-circle',  label: 'Cancelled' }
            };

            const s = statusColors[newStatus];
            if (!s) return;

            // Cari semua elemen dengan id status-badge-{orderId}
            const badges = document.querySelectorAll('#status-badge-' + orderId);

            badges.forEach(badge => {
                // Desktop: elemen .space-y-1 yang berisi span status
                const statusSpan = badge.querySelector ? badge.querySelector('span') : badge;
                const target = badge.classList.contains('space-y-1') ? badge.querySelector('span') : badge;

                if (target) {
                    // Hapus semua warna lama
                    target.className = target.className
                        .replace(/bg-(yellow|blue|green|red)-100/g, '')
                        .replace(/text-(yellow|blue|green|red)-800/g, '')
                        .trim();

                    // Tambahkan warna baru
                    target.classList.add(s.bg, s.text);

                    // Update icon & label
                    const icon = target.querySelector('i');
                    if (icon) {
                        icon.className = `fas ${s.icon} mr-1`;
                    }

                    // Update text label (hanya label, bukan icon)
                    const textNode = Array.from(target.childNodes).find(node => node.nodeType === Node.TEXT_NODE);
                    if (textNode) {
                        textNode.textContent = '\n                                            ' + s.label;
                    } else {
                        // Fallback: set innerText
                        target.innerHTML = `<i class="fas ${s.icon} mr-1"></i>${s.label}`;
                    }
                }
            });
        }

        // Update tombol aksi setelah perubahan status
        function updateActionButtons(orderId, newStatus) {
            // Cari semua actions container (bisa ada di desktop, tablet, mobile)
            const allActions = document.querySelectorAll('#actions-' + orderId);

            allActions.forEach(container => {
                // Hapus tombol status yang lama (Diproses / Selesai)
                const oldStatusBtns = container.querySelectorAll('.btn-status-change');
                oldStatusBtns.forEach(btn => btn.remove());

                // Cari referensi tombol "Detail" untuk menyisipkan sebelum-nya
                const detailBtn = container.querySelector('[data-btn="detail"]');

                if (newStatus === 'processing') {
                    // Tambahkan tombol "Selesai"
                    const doneBtn = document.createElement('button');
                    doneBtn.className = 'btn-status-change inline-flex items-center gap-1 bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition shadow-sm hover:shadow';
                    doneBtn.innerHTML = '<i class="fas fa-check-circle"></i><span>Selesai</span>';
                    doneBtn.onclick = () => updateOrderStatus(orderId, 'done');

                    // Cek apakah ini tampilan mobile/tablet (flex-1) atau desktop
                    if (container.closest('.hidden.lg\\:block') || container.closest('td')) {
                        // Desktop
                    } else {
                        // Mobile / Tablet
                        doneBtn.className = 'btn-status-change flex-1 bg-green-500 hover:bg-green-600 text-white py-2.5 rounded-lg text-sm font-medium transition shadow-sm';
                        doneBtn.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Selesai';
                    }

                    if (detailBtn) {
                        container.insertBefore(doneBtn, detailBtn);
                    } else {
                        container.insertBefore(doneBtn, container.firstChild);
                    }
                }
                // Jika sudah "done", tidak tambahkan tombol baru (correct behavior)
            });
        }

        // Tampilkan Toast Notification
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');

            const colors = {
                success: { bg: 'bg-green-600', icon: 'fa-check-circle' },
                error:   { bg: 'bg-red-600',   icon: 'fa-exclamation-circle' }
            };
            const c = colors[type] || colors.success;

            toast.className = `${c.bg} text-white px-5 py-3 rounded-lg shadow-lg flex items-center gap-3 pointer-events-auto animate-slide-in max-w-sm`;
            toast.innerHTML = `<i class="fas ${c.icon} text-lg flex-shrink-0"></i><span class="text-sm font-medium">${message}</span>`;

            container.appendChild(toast);

            // Auto-remove setelah 3 detik
            setTimeout(() => {
                toast.classList.add('opacity-0');
                toast.style.transition = 'opacity 0.3s ease';
                setTimeout(() => toast.remove(), 350);
            }, 3000);
        }

        // Helper: Ambil cookie (fallback untuk CSRF)
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return '';
        }

        // =============================================
        // FUNGSI FILTER, SEARCH, MODAL (ORIGINAL)
        // =============================================

        // Filter Orders
        function filterOrders(status) {
            const rows = document.querySelectorAll('.order-row');
            const tabs = document.querySelectorAll('.filter-tab');

            tabs.forEach(tab => {
                tab.classList.remove('border-blue-600', 'text-blue-600', 'active');
                tab.classList.add('border-transparent', 'text-gray-600');
            });
            event.target.classList.add('border-blue-600', 'text-blue-600', 'active');
            event.target.classList.remove('border-transparent', 'text-gray-600');

            rows.forEach(row => {
                if (status === 'all' || row.dataset.status === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Search Orders
        function searchOrders() {
            const searchValue = document.getElementById('searchOrder').value.toLowerCase();
            const orderRows = document.querySelectorAll('.order-row');

            orderRows.forEach(row => {
                const orderId = row.querySelector('.order-id')?.textContent.toLowerCase() || '';
                const customer = row.querySelector('.order-customer')?.textContent.toLowerCase() || '';
                const category = row.querySelector('.order-category')?.textContent.toLowerCase() || '';
                const whatsapp = row.querySelector('.order-whatsapp')?.textContent.toLowerCase() || '';

                const matchFound = orderId.includes(searchValue) ||
                    customer.includes(searchValue) ||
                    category.includes(searchValue) ||
                    whatsapp.includes(searchValue);

                row.style.display = matchFound ? '' : 'none';
            });
        }

        // View Order Detail
        function viewOrderDetail(orderId) {
            const modal = document.getElementById('orderDetailModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Reset content to loading
            document.getElementById('orderDetailContent').innerHTML = `
                <div class="flex items-center justify-center py-8">
                    <div class="text-center">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-3"></div>
                        <p class="text-gray-600">Memuat detail pesanan...</p>
                    </div>
                </div>`;

            fetch(`/admin/orders/${orderId}/detail`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('orderDetailContent').innerHTML = renderOrderDetail(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('orderDetailContent').innerHTML =
                        '<div class="text-center py-8"><i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-3"></i><p class="text-red-600 font-medium">Gagal memuat detail pesanan</p><p class="text-gray-500 text-sm mt-2">Silakan coba lagi nanti</p></div>';
                });
        }

        // Close Order Detail
        function closeOrderDetail() {
            document.getElementById('orderDetailModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Render Order Detail
        function renderOrderDetail(order) {
            const dpStatus = order.dp_status !== 'orders' ?
                `<span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full
                ${order.dp_status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                  order.dp_status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                DP: ${order.dp_status.charAt(0).toUpperCase() + order.dp_status.slice(1)}
                </span>` : '';

            const downloadBtn = order.image_path ?
                `<button onclick="downloadOrderImage('${order.image_path}', ${order.id})"
                class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                <i class="fas fa-download"></i> Download
                </button>` : '';

            // Tombol status di modal detail
            let modalStatusBtn = '';
            if (order.queue_status === 'waiting') {
                modalStatusBtn = `
                    <button onclick="updateOrderStatus(${order.id}, 'processing'); closeOrderDetail();"
                        class="flex-1 inline-flex items-center justify-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2.5 sm:py-3 rounded-lg text-sm font-medium transition shadow-md hover:shadow-lg">
                        <i class="fas fa-spinner text-lg"></i> <span>Diproses</span>
                    </button>`;
            } else if (order.queue_status === 'processing') {
                modalStatusBtn = `
                    <button onclick="updateOrderStatus(${order.id}, 'done'); closeOrderDetail();"
                        class="flex-1 inline-flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2.5 sm:py-3 rounded-lg text-sm font-medium transition shadow-md hover:shadow-lg">
                        <i class="fas fa-check-circle text-lg"></i> <span>Selesai</span>
                    </button>`;
            }

            return `
        <div class="space-y-6">
            <!-- Header Info -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-3 sm:p-4 md:p-6 border border-blue-200">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                    <div class="bg-white rounded p-2 md:p-3">
                        <label class="text-xs text-gray-600 font-medium block mb-1">Order ID</label>
                        <p class="text-lg md:text-xl font-bold text-gray-900">#${String(order.id).padStart(5, '0')}</p>
                    </div>
                    <div class="bg-white rounded p-2 md:p-3">
                        <label class="text-xs text-gray-600 font-medium block mb-1">Tanggal</label>
                        <p class="text-lg md:text-xl font-bold text-gray-900">${new Date(order.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</p>
                    </div>
                    <div class="bg-white rounded p-2 md:p-3">
                        <label class="text-xs text-gray-600 font-medium block mb-1">Antrian</label>
                        <p class="text-lg md:text-xl font-bold text-blue-600">${order.queue_position}/${order.queue_number}</p>
                    </div>
                    <div class="bg-white rounded p-2 md:p-3">
                        <label class="text-xs text-gray-600 font-medium block mb-1">Priority</label>
                        <p class="text-lg md:text-xl font-bold">${order.is_priority ? '<i class="fas fa-star text-yellow-500"></i> Ya' : 'Tidak'}</p>
                    </div>
                </div>
            </div>

            <!-- Status Section -->
            <div class="border-t pt-4">
                <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-600"></i> Status Pesanan
                </h4>
                <div class="flex flex-wrap gap-2 sm:gap-3">
                    <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full
                    ${order.queue_status === 'waiting' ? 'bg-yellow-100 text-yellow-800' :
                      order.queue_status === 'processing' ? 'bg-blue-100 text-blue-800' :
                      order.queue_status === 'done' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                    <i class="fas fa-${order.queue_status === 'waiting' ? 'clock' :
                      order.queue_status === 'processing' ? 'spinner' :
                      order.queue_status === 'done' ? 'check-circle' : 'times-circle'} mr-2"></i>
                    ${order.queue_status.charAt(0).toUpperCase() + order.queue_status.slice(1)}
                    </span>
                    ${dpStatus}
                </div>
            </div>

            <!-- Customer Info -->
            <div class="border-t pt-4">
                <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-user text-green-600"></i> Informasi Pelanggan
                </h4>
                <div class="bg-gray-50 rounded-lg p-3 sm:p-4 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                        <span class="text-gray-600 font-medium">Nama:</span>
                        <span class="font-semibold text-gray-900 break-words">${order.user.name}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 border-t pt-3">
                        <span class="text-gray-600 font-medium">WhatsApp:</span>
                        <a href="https://wa.me/${order.user.whatsapp}" target="_blank" class="font-semibold text-green-600 hover:text-green-700 inline-flex items-center gap-1">
                            ${order.user.whatsapp} <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 border-t pt-3">
                        <span class="text-gray-600 font-medium">Email:</span>
                        <span class="font-semibold text-gray-900 break-words">${order.user.email || '-'}</span>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="border-t pt-4">
                <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-box text-purple-600"></i> Detail Produk
                </h4>
                <div class="bg-gray-50 rounded-lg p-3 sm:p-4 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:justify-between gap-2">
                        <span class="text-gray-600 font-medium">Kategori:</span>
                        <span class="font-semibold text-gray-900 capitalize">${order.category}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between gap-2 border-t pt-3">
                        <span class="text-gray-600 font-medium">Ukuran:</span>
                        <span class="font-semibold text-gray-900">${order.size}</span>
                    </div>
                    ${order.variant ? `<div class="flex flex-col sm:flex-row sm:justify-between gap-2 border-t pt-3">
                        <span class="text-gray-600 font-medium">Varian:</span>
                        <span class="font-semibold text-gray-900">${order.variant}</span>
                    </div>` : ''}
                    ${order.description ? `<div class="border-t pt-3">
                        <span class="text-gray-600 font-medium block mb-2">Deskripsi:</span>
                        <p class="text-gray-900 text-sm bg-white rounded p-2">${order.description}</p>
                    </div>` : ''}
                </div>
            </div>

            <!-- Product Image -->
            ${order.image_path ? `
            <div class="border-t pt-4">
                <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-image text-indigo-600"></i> Gambar Pesanan
                </h4>
                <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                    <img src="/storage/${order.image_path}" alt="Order Image" class="w-full h-auto max-h-96 object-cover rounded-lg border border-gray-200 mb-3">
                    <div class="flex gap-2 flex-wrap">
                        ${downloadBtn}
                        <a href="/storage/${order.image_path}" target="_blank"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-external-link-alt"></i> Lihat
                        </a>
                    </div>
                </div>
            </div>
            ` : ''}

            <!-- Payment Info -->
            <div class="border-t pt-4">
                <h4 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-credit-card text-orange-600"></i> Informasi Pembayaran
                </h4>
                <div class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-lg p-3 sm:p-4 space-y-3 border border-orange-200">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                        <span class="text-gray-700 font-medium">Total Harga:</span>
                        <span class="text-lg sm:text-xl font-bold text-gray-900">Rp ${Number(order.total_price).toLocaleString('id-ID')}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 border-t pt-3 border-orange-200">
                        <span class="text-gray-700 font-medium">DP Dibayar:</span>
                        <span class="text-lg sm:text-xl font-bold text-green-600">Rp ${Number(order.dp_amount).toLocaleString('id-ID')}</span>
                    </div>
                    ${order.remaining_price > 0 ? `
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 border-t pt-3 border-orange-200">
                        <span class="text-gray-700 font-medium">Sisa Pembayaran:</span>
                        <span class="text-lg sm:text-xl font-bold text-orange-600">Rp ${Number(order.remaining_price).toLocaleString('id-ID')}</span>
                    </div>
                    ` : `
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 border-t pt-3 border-orange-200">
                        <span class="text-gray-700 font-medium">Status Pembayaran:</span>
                        <span class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i> Lunas
                        </span>
                    </div>
                    `}
                </div>
            </div>

            <!-- Action Buttons (termasuk tombol status baru) -->
            <div class="border-t pt-4 flex gap-2 flex-col sm:flex-row">
                ${modalStatusBtn}
                <a href="https://wa.me/${order.user.whatsapp}" target="_blank"
                class="flex-1 inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 sm:py-3 rounded-lg text-sm font-medium transition shadow-md hover:shadow-lg">
                <i class="fab fa-whatsapp text-lg"></i> <span>Chat WhatsApp</span>
                </a>
                <button onclick="printOrderDetail(${order.id})"
                class="flex-1 inline-flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2.5 sm:py-3 rounded-lg text-sm font-medium transition shadow-md hover:shadow-lg">
                <i class="fas fa-print text-lg"></i> <span>Cetak</span>
                </button>
            </div>
        </div>
    `;
        }

        // Download Order Image
        function downloadOrderImage(imagePath, orderId) {
            const link = document.createElement('a');
            link.href = `/storage/${imagePath}`;
            link.download = `pesanan-${String(orderId).padStart(5, '0')}.jpg`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Print Order Detail
        function printOrderDetail(orderId) {
            window.open(`/admin/orders/${orderId}/print`, '_blank');
        }

        // Export Orders
        function exportOrders() {
            window.location.href = '/admin/orders/export';
        }

        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });

        // Close modal on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeOrderDetail();
            }
        });

        // Close modal on outside click
        document.getElementById('orderDetailModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeOrderDetail();
            }
        });
    </script>

    <!-- CSS untuk Toast Notification -->
    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .animate-slide-in {
            animation: slideIn 0.3s ease forwards;
        }
    </style>

@endsection
@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-4 sm:py-8">
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <h1 class="text-2xl sm:text-3xl font-bold mb-4 sm:mb-6 text-gray-800">Verifikasi Down Payment</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center justify-between">
                <span class="text-sm sm:text-base">{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.style.display='none'" class="font-bold text-lg">×</button>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex items-center justify-between">
                <span class="text-sm sm:text-base">{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.style.display='none'" class="font-bold text-lg">×</button>
            </div>
        @endif

        <!-- Tabs -->
        <div class="mb-4 sm:mb-6 border-b border-gray-200 overflow-x-auto">
            <div class="flex space-x-2 sm:space-x-8 min-w-max sm:min-w-0">
                <button class="tab-btn active px-3 sm:px-4 py-2 border-b-2 border-yellow-600 text-yellow-600 font-medium whitespace-nowrap text-sm sm:text-base" 
                    data-color="yellow" onclick="switchTab('pending', this)">
                    Pending <span class="bg-yellow-600 text-white rounded-full px-2 py-1 text-xs ml-1 sm:ml-2">{{ $pendingCount }}</span>
                </button>
                <button class="tab-btn px-3 sm:px-4 py-2 border-b-2 border-transparent text-gray-600 font-medium hover:text-gray-800 whitespace-nowrap text-sm sm:text-base" 
                    data-color="green" onclick="switchTab('verified', this)">
                    Terverifikasi <span class="bg-green-600 text-white rounded-full px-2 py-1 text-xs ml-1 sm:ml-2">{{ $verifiedCount }}</span>
                </button>
                <button class="tab-btn px-3 sm:px-4 py-2 border-b-2 border-transparent text-gray-600 font-medium hover:text-gray-800 whitespace-nowrap text-sm sm:text-base" 
                    data-color="red" onclick="switchTab('rejected', this)">
                    Ditolak <span class="bg-red-600 text-white rounded-full px-2 py-1 text-xs ml-1 sm:ml-2">{{ $rejectedCount }}</span>
                </button>
            </div>
        </div>

        <!-- PENDING DP -->
        <div id="pending" class="tab-content space-y-4">
            @if ($pendingDp->count() > 0)
                @foreach ($pendingDp as $dp)
                    <div class="border-2 border-yellow-200 rounded-lg p-4 sm:p-5 bg-yellow-50 hover:shadow-lg transition">
                        <!-- Header Info -->
                        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4 mb-4">
                            <div class="bg-white p-3 rounded border border-yellow-100">
                                <p class="text-xs text-gray-500 uppercase font-semibold">Order ID</p>
                                <p class="text-xl sm:text-2xl font-bold text-gray-800">#{{ $dp->id }}</p>
                                <p class="text-xs sm:text-sm text-gray-600 truncate">{{ $dp->product_name }}</p>
                            </div>

                            <div class="bg-white p-3 rounded border border-yellow-100">
                                <p class="text-xs text-gray-500 uppercase font-semibold">Customer</p>
                                <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">{{ $dp->user->name }}</p>
                                <p class="text-xs sm:text-sm text-gray-600">{{ $dp->user->phone ?? '-' }}</p>
                            </div>

                            <div class="bg-white rounded p-3 border border-blue-200">
                                <p class="text-xs text-gray-500 uppercase font-semibold">Harga Pesanan</p>
                                <p class="text-base sm:text-xl font-bold text-blue-600">Rp {{ number_format($dp->total_price, 0, ',', '.') }}</p>
                            </div>

                            <div class="bg-white rounded p-3 border border-green-200">
                                <p class="text-xs text-gray-500 uppercase font-semibold">DP Kali Ini</p>
                                <p class="text-base sm:text-xl font-bold text-green-600">Rp {{ number_format($dp->dp_amount ?? 0, 0, ',', '.') }}</p>
                            </div>

                            <div class="bg-white rounded p-3 border border-orange-200 col-span-2 lg:col-span-1">
                                <p class="text-xs text-gray-500 uppercase font-semibold">Sisa Bayar</p>
                                <p class="text-base sm:text-xl font-bold text-orange-600">Rp {{ number_format($dp->remaining_price ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <!-- Payment Details -->
                        <div class="border-t pt-4 mb-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">📋 Detail Pembayaran:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                                <div class="bg-white p-2 rounded border border-gray-200">
                                    <p class="text-gray-500 text-xs">Tanggal Ajukan:</p>
                                    <p class="font-semibold text-sm sm:text-base">{{ $dp->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <div class="bg-white p-2 rounded border border-gray-200">
                                    <p class="text-gray-500 text-xs">Status DP:</p>
                                    <p class="font-semibold mt-0.5">
                                        <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded text-xs">{{ strtoupper($dp->dp_status ?? 'pending') }}</span>
                                    </p>
                                </div>
                                <div class="bg-white p-2 rounded border border-gray-200">
                                    <p class="text-gray-500 text-xs">DP Sebelumnya:</p>
                                    <p class="font-semibold text-sm sm:text-base">Rp {{ number_format(($dp->total_price - ($dp->remaining_price ?? 0) - ($dp->dp_amount ?? 0)), 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Bukti Pembayaran -->
                        <div class="border-t pt-4 mb-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">📸 Bukti Pembayaran:</p>
                            <div class="flex justify-center">
                                <img src="{{ Storage::url($dp->payment_proof) }}" alt="Bukti Pembayaran" 
                                    class="max-w-full max-h-60 sm:max-h-80 rounded-lg border-2 border-gray-300 hover:border-blue-500 transition cursor-pointer shadow-md object-contain"
                                    onclick="showFullImage('{{ Storage::url($dp->payment_proof) }}')">
                            </div>
                        </div>

                        <!-- Verification Buttons -->
                        <div class="border-t pt-4">
                            <form action="{{ route('admin.dp.verify') }}" method="POST">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $dp->id }}">
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <button type="submit" name="status" value="approved" 
                                        class="w-full sm:flex-1 bg-green-600 hover:bg-green-700 active:bg-green-800 text-white font-bold py-3 px-4 rounded-lg transition flex items-center justify-center gap-2"
                                        onclick="return confirm('Konfirmasi verifikasi DP?')">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm sm:text-base">Verifikasi</span>
                                    </button>
                                    <button type="submit" name="status" value="rejected" 
                                        class="w-full sm:flex-1 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white font-bold py-3 px-4 rounded-lg transition flex items-center justify-center gap-2"
                                        onclick="return confirm('Tolak bukti DP ini?')">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm sm:text-base">Tolak</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-8 sm:py-12">
                    <svg class="mx-auto h-12 w-12 sm:h-16 sm:w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-500 text-base sm:text-lg font-semibold">Tidak ada DP yang menunggu verifikasi</p>
                    <p class="text-gray-400 mt-1 text-sm sm:text-base">Semua DP sudah diverifikasi atau ditolak</p>
                </div>
            @endif
        </div>

        <!-- VERIFIED DP -->
        <div id="verified" class="tab-content hidden space-y-4">
            @if ($verifiedDp->count() > 0)
                @foreach ($verifiedDp as $dp)
                    <div class="border-2 border-green-200 rounded-lg p-4 sm:p-5 bg-green-50">
                        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
                            <div class="bg-white p-3 rounded border border-green-100">
                                <p class="text-xs text-gray-500 uppercase font-semibold">Order ID</p>
                                <p class="text-xl sm:text-2xl font-bold text-gray-800">#{{ $dp->id }}</p>
                            </div>
                            <div class="bg-white p-3 rounded border border-green-100">
                                <p class="text-xs text-gray-500 uppercase font-semibold">Customer</p>
                                <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">{{ $dp->user->name }}</p>
                            </div>
                            <div class="bg-white p-3 rounded border border-green-100">
                                <p class="text-xs text-gray-500 uppercase font-semibold">DP Amount</p>
                                <p class="text-base sm:text-lg font-bold text-green-600">Rp {{ number_format($dp->dp_amount ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-white p-3 rounded border border-green-100">
                                <p class="text-xs text-gray-500 uppercase font-semibold">Sisa Bayar</p>
                                <p class="text-base sm:text-lg font-bold text-orange-600">Rp {{ number_format($dp->remaining_price ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-white p-3 rounded border border-green-100 col-span-2 lg:col-span-1">
                                <p class="text-xs text-gray-500 uppercase font-semibold">Verifikasi</p>
                                <p class="text-sm font-semibold text-green-700">{{ $dp->updated_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-8 sm:py-12">
                    <p class="text-gray-500 text-base sm:text-lg">Belum ada DP yang terverifikasi</p>
                </div>
            @endif
        </div>

        <!-- REJECTED DP -->
        <div id="rejected" class="tab-content hidden space-y-4">
            @if ($rejectedDp->count() > 0)
                @foreach ($rejectedDp as $dp)
                    <div class="border-2 border-red-200 rounded-lg p-4 sm:p-5 bg-red-50">
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                            <div class="bg-white p-3 rounded border border-red-100">
                                <p class="text-xs text-gray-500 uppercase font-semibold">Order ID</p>
                                <p class="text-xl sm:text-2xl font-bold text-gray-800">#{{ $dp->id }}</p>
                            </div>
                            <div class="bg-white p-3 rounded border border-red-100">
                                <p class="text-xs text-gray-500 uppercase font-semibold">Customer</p>
                                <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">{{ $dp->user->name }}</p>
                            </div>
                            <div class="bg-white p-3 rounded border border-red-100">
                                <p class="text-xs text-gray-500 uppercase font-semibold">DP Amount</p>
                                <p class="text-base sm:text-lg font-bold text-red-600">Rp {{ number_format($dp->dp_amount ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-white p-3 rounded border border-red-100">
                                <p class="text-xs text-gray-500 uppercase font-semibold">Ditolak</p>
                                <p class="text-sm font-semibold text-red-700">{{ $dp->updated_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-8 sm:py-12">
                    <p class="text-gray-500 text-base sm:text-lg">Belum ada DP yang ditolak</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4" onclick="closeFullImage(event)">
    <div class="relative w-full max-w-4xl max-h-full flex items-center justify-center">
        <img id="fullImage" src="" alt="Full Size" class="max-w-full max-h-[90vh] rounded-lg object-contain">
        <button onclick="closeModal()" class="absolute top-2 right-2 sm:top-4 sm:right-4 bg-white rounded-full p-2 hover:bg-gray-200 shadow-lg">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>

<script>
    const colorMap = {
        yellow: { border: 'border-yellow-600', text: 'text-yellow-600' },
        green:  { border: 'border-green-600', text: 'text-green-600' },
        red:    { border: 'border-red-600',   text: 'text-red-600' }
    };

    function switchTab(tabName, btn) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });

        // Reset all tab buttons
        document.querySelectorAll('.tab-btn').forEach(b => {
            const color = colorMap[b.dataset.color];
            b.classList.remove(color.border, color.text);
            b.classList.add('border-transparent', 'text-gray-600');
        });

        // Show target tab
        document.getElementById(tabName).classList.remove('hidden');

        // Activate clicked button with its own color
        const activeColor = colorMap[btn.dataset.color];
        btn.classList.remove('border-transparent', 'text-gray-600');
        btn.classList.add(activeColor.border, activeColor.text);
    }

    function showFullImage(src) {
        document.getElementById('fullImage').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Close when clicking the backdrop (not the image itself)
    function closeFullImage(e) {
        if (e.target === document.getElementById('imageModal')) {
            closeModal();
        }
    }

    // Close on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
</script>
@endsection
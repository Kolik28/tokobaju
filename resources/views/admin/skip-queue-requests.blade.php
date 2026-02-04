@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-8">
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-5 sm:mb-6 text-gray-800">Kelola Permintaan Skip Antrian</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm sm:text-base">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm sm:text-base">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tabs -->
        <div class="mb-5 sm:mb-6 border-b border-gray-200 overflow-x-auto">
            <div class="flex space-x-4 sm:space-x-8 min-w-max sm:min-w-0">
                <button class="tab-btn active px-3 sm:px-4 py-2 border-b-2 border-blue-600 text-blue-600 font-medium text-sm sm:text-base whitespace-nowrap" 
                    onclick="switchTab('pending', this)">
                    Pending <span class="bg-blue-600 text-white rounded-full px-2 py-0.5 text-xs ml-1 sm:ml-2">{{ $pendingCount }}</span>
                </button>
                <button class="tab-btn px-3 sm:px-4 py-2 border-b-2 border-transparent text-gray-600 font-medium hover:text-gray-800 text-sm sm:text-base whitespace-nowrap" 
                    onclick="switchTab('approved', this)">
                    Disetujui <span class="bg-green-600 text-white rounded-full px-2 py-0.5 text-xs ml-1 sm:ml-2">{{ $approvedCount }}</span>
                </button>
                <button class="tab-btn px-3 sm:px-4 py-2 border-b-2 border-transparent text-gray-600 font-medium hover:text-gray-800 text-sm sm:text-base whitespace-nowrap" 
                    onclick="switchTab('rejected', this)">
                    Ditolak <span class="bg-red-600 text-white rounded-full px-2 py-0.5 text-xs ml-1 sm:ml-2">{{ $rejectedCount }}</span>
                </button>
            </div>
        </div>

        <!-- PENDING REQUESTS -->
        <div id="pending" class="tab-content">
            @if ($pendingRequests->count() > 0)
                <div class="space-y-4">
                    @foreach ($pendingRequests as $request)
                        <div class="border border-yellow-200 rounded-lg p-3 sm:p-4 bg-yellow-50">

                            <!-- Info Grid: 1 col mobile → 2 col sm → 4 col md -->
                            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-4">
                                <!-- Order ID -->
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Order ID</p>
                                    <p class="text-base sm:text-lg font-bold text-gray-800">#{{ $request->id }}</p>
                                </div>

                                <!-- User Info -->
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">User</p>
                                    <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">{{ $request->user->name }}</p>
                                    <p class="text-xs sm:text-sm text-gray-600 truncate">{{ $request->user->email }}</p>
                                </div>

                                <!-- Skip Info -->
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Detail Skip</p>
                                    <p class="font-semibold text-gray-800 text-sm sm:text-base">Salip {{ $request->priority_level }} orang</p>
                                    <p class="text-xs sm:text-sm text-gray-600">Rp {{ number_format($request->skip_amount, 0, ',', '.') }}</p>
                                </div>

                                <!-- Current Position -->
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Posisi</p>
                                    <p class="text-base sm:text-lg font-bold text-gray-800">
                                        {{ $request->queue_position }}
                                        <span class="text-gray-400 text-xs sm:text-sm font-normal">→</span>
                                        <span class="text-green-600">{{ max(1, $request->queue_position - $request->priority_level) }}</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Bukti Pembayaran -->
                            <div class="mb-3 sm:mb-4 border-t pt-3 sm:pt-4">
                                <p class="text-xs sm:text-sm font-semibold text-gray-700 mb-2">Bukti Pembayaran:</p>
                                <div class="flex justify-center">
                                    <a href="{{ Storage::url($request->skip_proof) }}" target="_blank" class="inline-block">
                                        <img src="{{ Storage::url($request->skip_proof) }}" alt="Bukti Pembayaran" 
                                            class="max-h-48 sm:max-h-64 w-full object-contain rounded-lg border border-gray-300 hover:border-blue-500 transition cursor-pointer">
                                    </a>
                                </div>
                            </div>

                            <!-- Tanggal Request -->
                            <div class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4">
                                <p>Diajukan: {{ $request->created_at->format('d M Y H:i') }}</p>
                            </div>

                            <!-- Action Buttons: stack on mobile, row on sm+ -->
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                                <form action="{{ route('admin.skip-queue.approve') }}" method="POST" class="w-full sm:flex-1">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $request->id }}">
                                    <input type="hidden" name="skip_status" value="approved">
                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 active:bg-green-800 text-white font-bold py-2.5 sm:py-2 px-4 rounded-lg transition text-sm sm:text-base">
                                        ✓ Setujui
                                    </button>
                                </form>
                                <form action="{{ route('admin.skip-queue.approve') }}" method="POST" class="w-full sm:flex-1">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $request->id }}">
                                    <input type="hidden" name="skip_status" value="rejected">
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 active:bg-red-800 text-white font-bold py-2.5 sm:py-2 px-4 rounded-lg transition text-sm sm:text-base" 
                                        onclick="return confirm('Apakah Anda yakin ingin menolak permintaan ini?')">
                                        ✗ Tolak
                                    </button>
                                </form>
                                <a href="{{ Storage::url($request->skip_proof) }}" target="_blank" 
                                    class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold py-2.5 sm:py-2 px-4 rounded-lg text-center transition text-sm sm:text-base">
                                    👁️ Lihat Bukti
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 sm:py-12">
                    <p class="text-gray-400 text-4xl mb-3">📋</p>
                    <p class="text-gray-500 text-base sm:text-lg">Tidak ada permintaan skip yang menunggu verifikasi</p>
                </div>
            @endif
        </div>

        <!-- APPROVED REQUESTS -->
        <div id="approved" class="tab-content hidden">
            @if ($approvedRequests->count() > 0)
                <div class="space-y-3 sm:space-y-4">
                    @foreach ($approvedRequests as $request)
                        <div class="border border-green-200 rounded-lg p-3 sm:p-4 bg-green-50">
                            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase">Order ID</p>
                                    <p class="text-base sm:text-lg font-bold text-gray-800">#{{ $request->id }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase">User</p>
                                    <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">{{ $request->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase">Detail Skip</p>
                                    <p class="font-semibold text-gray-800 text-sm sm:text-base">Salip {{ $request->priority_level }} orang</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase">Posisi Baru</p>
                                    <p class="text-base sm:text-lg font-bold text-green-600">{{ $request->queue_position }}</p>
                                </div>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-600 mt-2">Disetujui: {{ $request->skip_verified_at->format('d M Y H:i') }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 sm:py-12">
                    <p class="text-gray-400 text-4xl mb-3">✅</p>
                    <p class="text-gray-500 text-base sm:text-lg">Tidak ada permintaan yang disetujui</p>
                </div>
            @endif
        </div>

        <!-- REJECTED REQUESTS -->
        <div id="rejected" class="tab-content hidden">
            @if ($rejectedRequests->count() > 0)
                <div class="space-y-3 sm:space-y-4">
                    @foreach ($rejectedRequests as $request)
                        <div class="border border-red-200 rounded-lg p-3 sm:p-4 bg-red-50">
                            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase">Order ID</p>
                                    <p class="text-base sm:text-lg font-bold text-gray-800">#{{ $request->id }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase">User</p>
                                    <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">{{ $request->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase">Permintaan</p>
                                    <p class="font-semibold text-gray-800 text-sm sm:text-base">Salip {{ $request->priority_level }} orang</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase">Alasan</p>
                                    <p class="text-xs sm:text-sm text-red-600 font-medium">Ditolak Admin</p>
                                </div>
                            </div>
                            <p class="text-xs sm:text-sm text-gray-600 mt-2">Ditolak: {{ $request->skip_verified_at->format('d M Y H:i') }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 sm:py-12">
                    <p class="text-gray-400 text-4xl mb-3">❌</p>
                    <p class="text-gray-500 text-base sm:text-lg">Tidak ada permintaan yang ditolak</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function switchTab(tabName, clickedBtn) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });

        // Remove active class from all buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('border-blue-600', 'text-blue-600');
            btn.classList.add('border-transparent', 'text-gray-600');
        });

        // Show selected tab
        document.getElementById(tabName).classList.remove('hidden');

        // Add active class to clicked button
        clickedBtn.classList.add('border-blue-600', 'text-blue-600');
        clickedBtn.classList.remove('border-transparent', 'text-gray-600');
    }
</script>
@endsection
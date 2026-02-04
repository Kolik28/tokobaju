@extends('layouts.app')

@section('content')
        <div class="max-w-2xl mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold mb-4 gradient-text">
                    Salip Antrian
                </h1>
                <p class="text-gray-600">
                    Pilih pesanan dan isi form di bawah untuk melakukan salip antrian
                </p>
            </div>

            {{-- Bagian Pemberitahuan Sesi (Success/Error) --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p class="font-bold">✓ Sukses!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p class="font-bold">✗ Gagal!</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif


            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="mb-6">
                    <h3 class="text-xl font-bold mb-2">Form Salip Antrian</h3>
                    <p class="text-sm text-gray-600">
                        Anda memiliki {{ $userOrders->count() }} pesanan aktif
                    </p>
                </div>

                <form action="{{ route('orders.skip') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf
                    
                    {{-- FITUR BARU: Dropdown Pilih Order --}}
                    <div class="space-y-2">
                        <label for="order_id" class="block text-sm font-medium">
                            📦 Pilih Pesanan
                        </label>
                        <select id="order_id" name="order_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('order_id') border-red-500 @enderror"
                            onchange="updateOrderInfo()" required>
                            <option value="">-- Pilih Pesanan --</option>
                            @foreach($userOrders as $order)
                                <option value="{{ $order->id }}" 
                                    data-position="{{ $order->queue_position }}"
                                    data-category="{{ $order->category }}"
                                    data-size="{{ $order->size }}"
                                    data-price="{{ number_format($order->total_price, 0, ',', '.') }}">
                                    ID: {{ $order->id }} | Posisi: #{{ $order->queue_position }} | {{ ucfirst($order->category) }} | Harga: Rp{{ number_format($order->total_price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        @error('order_id')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info Pesanan yang Dipilih --}}
                    <div id="orderInfoBox" class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded hidden">
                        <h4 class="font-semibold text-blue-900 mb-2">📋 Detail Pesanan</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm text-blue-900">
                            <div>
                                <span class="text-gray-600">Kategori:</span>
                                <p class="font-medium" id="infoCategory">-</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Ukuran:</span>
                                <p class="font-medium" id="infoSize">-</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Posisi Antrian Saat Ini:</span>
                                <p class="font-bold text-orange-600" id="infoPosition">#-</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Harga:</span>
                                <p class="font-medium" id="infoPrice">Rp -</p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Input Jumlah Salip --}}
                    <div class="space-y-2">
                        <label for="jumlah_salip" class="block text-sm font-medium">
                            🚀 Jumlah Antrian yang Ingin Disalip
                        </label>
                        <div class="relative">
                            <input type="number" id="jumlah_salip" name="jumlah_salip" 
                                placeholder="0" min="1"
                                class="w-full pl-5 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('jumlah_salip') border-red-500 @enderror"
                                required />
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-orange-600 font-medium">
                                💰 Rp 100.000 per Antrian
                            </p>
                            <p class="text-sm text-gray-700 font-semibold" id="total-cost">
                                Total: Rp 0
                            </p>
                        </div>
                        @error('jumlah_salip')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror

                        {{-- Preview Posisi Baru --}}
                        <div id="positionPreview" class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded hidden">
                            <p class="text-sm text-amber-900">
                                <span class="font-semibold">Posisi Antrian Baru:</span>
                                <span id="newPosition" class="font-bold text-amber-600">#-</span>
                            </p>
                        </div>
                    </div>

                    {{-- Upload Bukti Pembayaran --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium">
                            📸 Upload Bukti Pembayaran
                        </label>
                        <div
                            class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-orange-500 transition-colors cursor-pointer @error('image_url') border-red-500 @enderror">
                            <input type="file" id="image_url" name="image_url" accept="image/*"
                                onchange="displayFileName()" class="hidden" required />
                            <label for="image_url" class="cursor-pointer">
                                <svg class="mx-auto mb-2 text-gray-400" width="40" height="40" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                <p id="fileName" class="text-sm text-gray-500">
                                    Klik untuk upload foto bukti pembayaran
                                </p>
                            </label>
                        </div>
                        <p class="text-xs text-gray-500">
                            Format: JPG, PNG, GIF (Max 2MB)
                        </p>
                        @error('image_url')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info Rekening --}}
                    <div class="bg-gradient-to-r from-orange-50 to-yellow-50 p-4 rounded-lg border border-orange-200">
                        <h3 class="font-semibold mb-3 text-orange-900">🏦 Informasi Rekening Pembayaran:</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-700">Bank:</span>
                                <span class="text-gray-900 font-semibold">BCA</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-700">No. Rekening:</span>
                                <span class="text-gray-900 font-mono font-semibold">1234567890</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-700">Atas Nama:</span>
                                <span class="text-gray-900 font-semibold">UMKM Store</span>
                            </div>
                        </div>
                    </div>

                    {{-- Info Penting --}}
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                        <p class="text-sm text-blue-900">
                            <span class="font-semibold">⚠️ Penting:</span> 
                        </p>
                        <ul class="text-sm text-blue-900 mt-2 space-y-1 ml-4">
                            <li>✓ Pastikan Anda sudah melakukan transfer sesuai dengan jumlah antrian yang dipilih</li>
                            <li>✓ Bukti pembayaran harus jelas dan terlihat</li>
                            <li>✓ Admin akan memverifikasi pembayaran Anda dalam waktu 24 jam</li>
                            <li>✓ Posisi antrian akan diperbarui setelah pembayaran terverifikasi</li>
                        </ul>
                    </div>

                    <button type="submit"
                        class="w-full bg-orange-500 text-white font-semibold py-3 rounded-md hover:bg-orange-600 transition-colors shadow-md hover:shadow-lg">
                        ✓ Kirim Bukti Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </main>

    <script>
        /**
         * Update info pesanan saat dropdown berubah
         */
        function updateOrderInfo() {
            const selectElement = document.getElementById('order_id');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const infoBox = document.getElementById('orderInfoBox');
            const positionPreview = document.getElementById('positionPreview');

            if (selectedOption.value === '') {
                infoBox.classList.add('hidden');
                positionPreview.classList.add('hidden');
                return;
            }

            // Update info box dengan data dari option
            document.getElementById('infoCategory').textContent = selectedOption.dataset.category.charAt(0).toUpperCase() + selectedOption.dataset.category.slice(1);
            document.getElementById('infoSize').textContent = selectedOption.dataset.size + ' cm';
            document.getElementById('infoPosition').textContent = '#' + selectedOption.dataset.position;
            document.getElementById('infoPrice').textContent = 'Rp ' + selectedOption.dataset.price;

            infoBox.classList.remove('hidden');

            // Trigger position preview update
            updatePositionPreview();
        }

        /**
         * Update preview posisi antrian baru
         */
        function updatePositionPreview() {
            const jumlahInput = document.getElementById('jumlah_salip');
            const selectElement = document.getElementById('order_id');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const positionPreview = document.getElementById('positionPreview');
            const newPositionSpan = document.getElementById('newPosition');

            if (!selectedOption.value || !jumlahInput.value) {
                positionPreview.classList.add('hidden');
                return;
            }

            const currentPos = parseInt(selectedOption.dataset.position);
            const jumlahSalip = parseInt(jumlahInput.value) || 0;
            const newPos = Math.max(1, currentPos - jumlahSalip);

            if (newPos === currentPos) {
                positionPreview.classList.add('hidden');
            } else {
                newPositionSpan.textContent = '#' + newPos;
                positionPreview.classList.remove('hidden');
            }
        }

        /**
         * Display nama file yang di-upload
         */
        function displayFileName() {
            const input = document.getElementById('image_url');
            const fileName = document.getElementById('fileName');

            if (input.files && input.files[0]) {
                fileName.textContent = input.files[0].name;
            }
        }

        /**
         * Update total cost ketika jumlah_salip berubah
         */
        document.getElementById('jumlah_salip').addEventListener('input', function() {
            const jumlah = parseInt(this.value) || 0;
            const totalCost = jumlah * 100000;
            document.getElementById('total-cost').textContent = 'Total: Rp ' + totalCost.toLocaleString('id-ID');
            
            // Update position preview
            updatePositionPreview();
        });

        /**
         * Update order info saat halaman dimuat
         */
        document.addEventListener('DOMContentLoaded', function() {
            // Jika ada nilai default di dropdown, tampilkan infonya
            const orderSelect = document.getElementById('order_id');
            if (orderSelect.value) {
                updateOrderInfo();
            }
        });
    </script>
@endsection
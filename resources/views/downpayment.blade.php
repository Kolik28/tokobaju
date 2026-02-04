@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-4 gradient-text">
                Down Payment (DP)
            </h1>
            <p class="text-gray-600">
                Pilih pesanan dan bayar DP untuk memastikan pesanan Anda
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
                <h3 class="text-xl font-bold mb-2">Form Pembayaran DP</h3>
                <p class="text-sm text-gray-600">
                    Anda memiliki {{ $userOrders->count() }} pesanan yang memerlukan DP
                </p>
            </div>

            <form action="{{ route('orders.uploadDp') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                                data-total-price="{{ $order->total_price }}"
                                data-dp-amount="{{ $order->dp_amount ?? 0 }}"
                                data-remaining="{{ $order->remaining_price ?? $order->total_price }}">
                                ID: {{ $order->id }} | Posisi: #{{ $order->queue_position }} | {{ ucfirst($order->category) }} | Rp{{ number_format($order->total_price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('order_id')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Info Pesanan yang Dipilih --}}
                <div id="orderInfoBox" class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded hidden">
                    <h4 class="font-semibold text-blue-900 mb-3">📋 Detail Pesanan</h4>
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
                            <span class="text-gray-600">Posisi Antrian:</span>
                            <p class="font-bold text-orange-600" id="infoPosition">#-</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Harga Total:</span>
                            <p class="font-bold text-green-600" id="infoTotalPrice">Rp -</p>
                        </div>
                        <div class="col-span-2">
                            <span class="text-gray-600">Sisa yang Perlu Dibayar:</span>
                            <p class="font-bold text-orange-600" id="infoRemaining">Rp -</p>
                        </div>
                    </div>
                </div>

                {{-- Input Jumlah DP --}}
                <div class="space-y-2">
                    <label for="amount_fake" class="block text-sm font-medium">
                        💰 Jumlah DP
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">
                            Rp
                        </span>
                        <input type="hidden" id="amount" name="amount">
                        <input type="text" id="amount_fake" name="amount_fake" placeholder="0"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('amount') border-red-500 @enderror"
                            required />
                    </div>
                    <p class="text-sm text-gray-500">
                        Masukkan nominal dalam Rupiah (tanpa titik atau koma)
                    </p>
                    @error('amount')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Preview Kalkulasi DP --}}
                <div id="dpCalculationBox" class="bg-green-50 border-l-4 border-green-500 p-4 rounded hidden">
                    <h4 class="font-semibold text-green-900 mb-2">✓ Ringkasan DP</h4>
                    <div class="space-y-1 text-sm text-green-900">
                        <div class="flex justify-between">
                            <span>DP Kali Ini:</span>
                            <span id="previewCurrentDp" class="font-bold">Rp 0</span>
                        </div>
                        <div class="border-t border-green-300 pt-1 mt-1 flex justify-between">
                            <span class="font-bold">Total DP:</span>
                            <span id="previewTotalDp" class="font-bold text-green-600">Rp 0</span>
                        </div>
                        <div class="flex justify-between pt-1">
                            <span>Sisa Pembayaran:</span>
                            <span id="previewRemaining" class="font-medium">Rp -</span>
                        </div>
                        <div id="dpStatusBox" class="mt-2 p-2 rounded" hidden>
                            <p id="dpStatusText" class="text-sm font-semibold"></p>
                        </div>
                    </div>
                </div>

                {{-- Upload Bukti Pembayaran --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium">
                        📸 Upload Bukti Pembayaran
                    </label>
                    <div
                        class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-orange-500 transition-colors cursor-pointer @error('payment_proof') border-red-500 @enderror">
                        <input type="file" id="payment_proof" name="payment_proof" accept="image/*" 
                            onchange="displayFileName()" class="hidden" required />
                        <label for="payment_proof" class="cursor-pointer">
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
                    @error('payment_proof')
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
                        <li>✓ DP minimal 50% dari total harga pesanan</li>
                        <li>✓ Pastikan Anda sudah melakukan transfer sesuai nominal yang ditampilkan</li>
                        <li>✓ Bukti pembayaran harus jelas dan terlihat</li>
                        <li>✓ Admin akan memverifikasi pembayaran Anda dalam waktu 24 jam</li>
                        <li>✓ Setelah DP disetujui, Anda dapat melanjutkan pesanan</li>
                    </ul>
                </div>

                <button type="submit"
                    class="w-full bg-orange-500 text-white font-semibold py-3 rounded-md hover:bg-orange-600 transition-colors shadow-md hover:shadow-lg">
                    ✓ Kirim Bukti Pembayaran DP
                </button>
            </form>
        </div>
    </div>
    </main>

    <script>
        /**
         * Format angka menjadi format Rupiah dengan pemisah ribuan
         */
        function formatRupiah(angka) {
            if (!angka) return '0';

            const number_string = angka.toString();
            const sisa = number_string.length % 3;
            let rupiah = number_string.substr(0, sisa);
            const ribuan = number_string.substr(sisa).match(/\d{3}/g);

            if (ribuan) {
                const separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            return rupiah;
        }

        /**
         * Update info pesanan saat dropdown berubah
         */
        function updateOrderInfo() {
            const selectElement = document.getElementById('order_id');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const infoBox = document.getElementById('orderInfoBox');

            if (selectedOption.value === '') {
                infoBox.classList.add('hidden');
                document.getElementById('dpCalculationBox').classList.add('hidden');
                // Clear amount input
                document.getElementById('amount_fake').value = '';
                document.getElementById('amount').value = '';
                return;
            }

            // Update info box dengan data dari option
            const totalPrice = parseInt(selectedOption.dataset.totalPrice);
            const dpAmount = parseInt(selectedOption.dataset.dpAmount) || 0;
            const remaining = parseInt(selectedOption.dataset.remaining) || totalPrice;

            document.getElementById('infoCategory').textContent = 
                selectedOption.dataset.category.charAt(0).toUpperCase() + selectedOption.dataset.category.slice(1);
            document.getElementById('infoSize').textContent = selectedOption.dataset.size + ' cm';
            document.getElementById('infoPosition').textContent = '#' + selectedOption.dataset.position;
            document.getElementById('infoTotalPrice').textContent = 'Rp ' + formatRupiah(totalPrice.toString());
            document.getElementById('infoRemaining').textContent = 'Rp ' + formatRupiah(remaining.toString());

            infoBox.classList.remove('hidden');

            // Trigger calculation update
            updateDpCalculation();
        }

        /**
         * Update preview kalkulasi DP
         */
        function updateDpCalculation() {
            const selectElement = document.getElementById('order_id');
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const amountInput = document.getElementById('amount');
            const calculationBox = document.getElementById('dpCalculationBox');
            const dpStatusBox = document.getElementById('dpStatusBox');

            if (!selectedOption.value || !amountInput.value) {
                calculationBox.classList.add('hidden');
                return;
            }

            const totalPrice = parseInt(selectedOption.dataset.totalPrice);
            const previousDp = parseInt(selectedOption.dataset.dpAmount) || 0;
            const currentDp = parseInt(amountInput.value) || 0;
            const totalDp = previousDp + currentDp;
            const remaining = totalPrice - totalDp;

            // Update preview values
            document.getElementById('previewCurrentDp').textContent = 'Rp ' + formatRupiah(currentDp.toString());
            document.getElementById('previewTotalDp').textContent = 'Rp ' + formatRupiah(totalDp.toString());
            document.getElementById('previewRemaining').textContent = 'Rp ' + formatRupiah(remaining.toString());

            // Status check
            const statusText = document.getElementById('dpStatusText');
            const minDpRequired = totalPrice * 0.5; // Minimum 50%

            if (totalDp > totalPrice) {
                // DP melebihi harga total
                dpStatusBox.classList.remove('hidden');
                dpStatusBox.classList.remove('bg-green-50', 'border-green-500');
                dpStatusBox.classList.add('bg-red-50', 'border-red-500');
                statusText.classList.remove('text-green-600');
                statusText.classList.add('text-red-600');
                statusText.textContent = '✗ DP tidak boleh melebihi harga pesanan';
            } else if (totalDp < minDpRequired) {
                // DP belum memenuhi minimum 50%
                dpStatusBox.classList.remove('hidden');
                dpStatusBox.classList.remove('bg-green-50', 'border-green-500');
                dpStatusBox.classList.add('bg-yellow-50', 'border-yellow-500');
                statusText.classList.remove('text-green-600', 'text-red-600');
                statusText.classList.add('text-yellow-600');
                statusText.textContent = '⚠️ DP minimal harus 50% dari total harga (Rp ' + formatRupiah(minDpRequired.toString()) + ')';
            } else {
                // DP memenuhi syarat
                dpStatusBox.classList.remove('hidden');
                dpStatusBox.classList.remove('bg-red-50', 'border-red-500', 'bg-yellow-50', 'border-yellow-500');
                dpStatusBox.classList.add('bg-green-50', 'border-green-500');
                statusText.classList.remove('text-red-600', 'text-yellow-600');
                statusText.classList.add('text-green-600');
                statusText.textContent = '✓ DP memenuhi syarat minimum (50%)';
            }

            calculationBox.classList.remove('hidden');
        }

        /**
         * Display nama file yang di-upload
         */
        function displayFileName() {
            const input = document.getElementById('payment_proof');
            const fileName = document.getElementById('fileName');
            if (input.files && input.files[0]) {
                fileName.textContent = '✓ ' + input.files[0].name;
            }
        }

        /**
         * Handle input angka dengan formatting otomatis
         */
        document.addEventListener('DOMContentLoaded', function() {
            const inputHarga = document.getElementById('amount_fake');
            const inputHidden = document.getElementById('amount');

            inputHarga.addEventListener('keyup', function(e) {
                // Ambil nilai input
                let value = e.target.value;

                // Hilangkan semua karakter non-digit
                value = value.replace(/[^0-9]/g, '');

                // Simpan nilai tanpa format ke input hidden
                inputHidden.value = value;

                // Format nilai dengan pemisah ribuan
                const formattedValue = formatRupiah(value);

                // Tampilkan nilai yang sudah diformat
                e.target.value = formattedValue;

                // Update calculation preview
                updateDpCalculation();
            });

            // Trigger info update jika ada default value
            const orderSelect = document.getElementById('order_id');
            if (orderSelect.value) {
                updateOrderInfo();
            }
        });
    </script>
@endsection
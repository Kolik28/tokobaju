@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">

        <form id="orderForm" action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col lg:grid lg:grid-cols-2 gap-4 lg:gap-8">
                <!-- Product Images -->
                <div class="space-y-2 order-1 lg:order-1">
                    <label class="block text-sm font-medium text-gray-700">
                        Masukkan Contoh Motif
                    </label>
                    
                    <!-- Upload Container -->
                    <div class="relative bg-gray-100 rounded-lg w-full aspect-square lg:aspect-auto lg:h-96 flex items-center justify-center overflow-hidden">
                        
                        <!-- Preview Image -->
                        <div id="preview-container" class="absolute inset-0 hidden w-full h-full">
                            <img id="preview-image" src="" alt="Preview"
                                class="w-full h-full object-contain bg-gray-100">
                        </div>

                        <!-- Upload Area -->
                        <div id="uploadArea" class="border-2 border-dashed border-gray-300 rounded-lg p-4 lg:p-8 text-center hover:border-orange-500 hover:bg-orange-50 transition-all duration-300 cursor-pointer w-full h-full flex flex-col items-center justify-center">
                            <input type="file" id="imageInput" name="image" accept="image/*" class="hidden" />
                            <label for="imageInput" class="cursor-pointer w-full h-full flex flex-col items-center justify-center">
                                <svg class="w-10 h-10 lg:w-12 lg:h-12 text-gray-400 mb-2" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                <p id="fileName" class="text-xs lg:text-sm text-gray-500 font-medium">
                                    Klik atau drag gambar ke sini
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    PNG, JPG, GIF max. 5MB
                                </p>
                            </label>
                        </div>

                        <!-- Error Message -->
                        <div id="imageError" class="hidden absolute bottom-2 left-2 right-2 bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded text-xs lg:text-sm">
                        </div>
                    </div>

                    <!-- Action Buttons (Below Preview) -->
                    <div id="imageButtonsContainer" class="hidden flex gap-2 flex-col sm:flex-row mt-3">
                        <button type="button" id="changeImageBtn" 
                            class="flex-1 bg-orange-500 hover:bg-orange-600 text-white px-3 lg:px-4 py-2 lg:py-3 rounded-lg font-medium transition duration-300 flex items-center justify-center gap-2 whitespace-nowrap text-sm lg:text-base">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span>Ganti Gambar</span>
                        </button>
                        <button type="button" id="removeImageBtn" 
                            class="flex-1 bg-red-500 hover:bg-red-600 text-white px-3 lg:px-4 py-2 lg:py-3 rounded-lg font-medium transition duration-300 flex items-center justify-center gap-2 whitespace-nowrap text-sm lg:text-base">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Hapus Gambar</span>
                        </button>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="order-2 lg:order-2">
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-6">
                        Formulir Pemesanan
                    </h1>

                    <!-- Kategori -->
                    <div class="mb-6">
                        <label for="categorySelect" class="block text-gray-700 font-medium mb-2 text-sm lg:text-base">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select name="category" id="categorySelect" 
                            class="w-full px-3 lg:px-4 py-2 lg:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm lg:text-base" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="airbrush">Airbrush</option>
                            <option value="polosan">Polosan</option>
                            <option value="fullset">Fullset</option>
                        </select>
                        <p id="categoryError" class="text-red-500 text-xs lg:text-sm mt-1 hidden"></p>
                    </div>

                    <!-- Ukuran -->
                    <div class="mb-6">
                        <label for="sizeInput" class="block text-gray-700 font-medium mb-2 text-sm lg:text-base">
                            Ukuran (cm) - Max 300
                        </label>
                        <input type="number" name="size" id="sizeInput" min="1" max="300"
                            class="w-full px-3 lg:px-4 py-2 lg:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm lg:text-base" 
                            placeholder="Masukkan ukuran" value="100" required>
                        <p id="sizeError" class="text-red-500 text-xs lg:text-sm mt-1 hidden"></p>
                    </div>

                    <!-- Varian (Select Option) -->
                    <div id="variantContainer" class="mb-6 hidden">
                        <label for="variantSelect" class="block text-gray-700 font-medium mb-2 text-sm lg:text-base">
                            Pilih Varian <span class="text-red-500">*</span>
                        </label>
                        <select name="variant" id="variantSelect" 
                            class="w-full px-3 lg:px-4 py-2 lg:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm lg:text-base">
                        </select>
                        <p id="variantError" class="text-red-500 text-xs lg:text-sm mt-1 hidden">Silakan pilih varian terlebih dahulu!</p>
                    </div>

                    <!-- Pesan -->
                    <div class="mb-6">
                        <label for="messageInput" class="block text-gray-700 font-medium mb-2 text-sm lg:text-base">
                            Pesan (Opsional)
                        </label>
                        <textarea name="message" id="messageInput" rows="4" 
                            class="w-full px-3 lg:px-4 py-2 lg:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm lg:text-base"
                            placeholder="Masukkan pesan atau catatan tambahan"></textarea>
                    </div>

                    <!-- Total Harga -->
                    <div class="mb-8">
                        <label class="block text-gray-700 font-medium mb-2 text-sm lg:text-base">Total Harga</label>
                        <input type="hidden" name="amount" id="amountInput" value="0">
                        <h4 class="text-xl lg:text-2xl font-bold text-red-500" id="totalPrice">Rp 0</h4>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="submit" id="submit-btn"
                            class="flex-1 bg-green-600 text-white py-2 lg:py-3 px-4 rounded-lg font-medium hover:bg-green-700 transition duration-300 flex items-center justify-center gap-2 text-sm lg:text-base">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Pesan Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Helper functions
        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }

        function hideError(elementId) {
            const errorElement = document.getElementById(elementId);
            errorElement.classList.add('hidden');
        }

        // Preview Image Handler
        function handleImageUpload(file) {
            const fileName = document.getElementById('fileName');
            const previewContainer = document.getElementById('preview-container');
            const previewImage = document.getElementById('preview-image');
            const uploadArea = document.getElementById('uploadArea');
            const imageInput = document.getElementById('imageInput');
            const buttonsContainer = document.getElementById('imageButtonsContainer');

            if (file) {
                // Validasi ukuran file (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    showError('imageError', 'Ukuran file maksimal 5MB');
                    imageInput.value = '';
                    return;
                }

                // Validasi tipe file
                if (!file.type.startsWith('image/')) {
                    showError('imageError', 'File harus berupa gambar');
                    imageInput.value = '';
                    return;
                }

                // Preview image
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImage.src = event.target.result;
                    previewContainer.classList.remove('hidden');
                    uploadArea.classList.add('hidden');
                    buttonsContainer.classList.remove('hidden');
                    hideError('imageError');
                }
                reader.readAsDataURL(file);
            }
        }

        // File input change event
        document.getElementById('imageInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                handleImageUpload(file);
            }
        });

        // Tombol Ganti Gambar
        document.getElementById('changeImageBtn').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('imageInput').click();
        });

        // Tombol Hapus Gambar
        document.getElementById('removeImageBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const previewContainer = document.getElementById('preview-container');
            const uploadArea = document.getElementById('uploadArea');
            const imageInput = document.getElementById('imageInput');
            const fileName = document.getElementById('fileName');
            const buttonsContainer = document.getElementById('imageButtonsContainer');

            previewContainer.classList.add('hidden');
            uploadArea.classList.remove('hidden');
            buttonsContainer.classList.add('hidden');
            imageInput.value = '';
            fileName.textContent = 'Klik atau drag gambar ke sini';
            hideError('imageError');
        });

        // Drag and drop support
        const uploadArea = document.getElementById('uploadArea');
        
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            uploadArea.classList.add('border-orange-500', 'bg-orange-50', 'border-2');
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            uploadArea.classList.remove('border-orange-500', 'bg-orange-50');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            uploadArea.classList.remove('border-orange-500', 'bg-orange-50');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const file = files[0];
                const imageInput = document.getElementById('imageInput');
                imageInput.files = files;
                handleImageUpload(file);
            }
        });

        // Category Change - Tampilkan Variant & Hitung Harga
        document.getElementById('categorySelect').addEventListener('change', updateVariantsAndPrice);
        document.getElementById('sizeInput').addEventListener('input', updatePrice);
        document.getElementById('variantSelect').addEventListener('change', updatePrice);

        function updateVariantsAndPrice() {
            const category = document.getElementById('categorySelect').value;
            const variantContainer = document.getElementById('variantContainer');
            const variantSelect = document.getElementById('variantSelect');

            variantSelect.innerHTML = '<option value="">-- Pilih Varian --</option>';

            if (category === 'airbrush') {
                variantContainer.classList.remove('hidden');
                variantSelect.innerHTML += '<option value="standar">Airbrush Aja</option>';
                variantSelect.innerHTML += '<option value="tambahan kain">Tambahan Kain</option>';
            } else if (category === 'fullset') {
                variantContainer.classList.remove('hidden');
                variantSelect.innerHTML += '<option value="standar">Permanent</option>';
                variantSelect.innerHTML += '<option value="welcro">Welcro</option>';
            } else if (category === 'polosan') {
                variantContainer.classList.add('hidden');
            }

            updatePrice();
        }

        function updatePrice() {
            const category = document.getElementById('categorySelect').value;
            const size = document.getElementById('sizeInput').value;
            const variant = document.getElementById('variantSelect').value;

            if (!category || !size) {
                document.getElementById('totalPrice').textContent = 'Rp 0';
                return;
            }

            fetch(`/api/calculate-price?category=${category}&size=${size}&variant=${variant}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('totalPrice').textContent = data.formatted_price;
                    // Update hidden input dengan nilai numerik jika diperlukan
                    if (data.price) {
                        document.getElementById('amountInput').value = data.price;
                    }
                })
                .catch(err => console.error('Error:', err));
        }
    </script>
@endsection
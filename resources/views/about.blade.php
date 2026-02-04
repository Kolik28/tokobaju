@extends('layouts.app')

@section('content')
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold mb-4 gradient-text p-2">
                Tentang Kami
            </h1>
            <p class="text-lg text-gray-600">
                Hubungi kami dan temukan lokasi kami
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 mb-12">
            <!-- Social Media Section -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold mb-4">Hubungi Kami</h3>
                <div class="space-y-4">
                    <a href="https://wa.me/628123456789" target="_blank" rel="noopener noreferrer" class="block">
                        <button class="w-full flex items-center gap-3 h-14 px-4 border border-gray-300 rounded-md hover:shadow-md hover:border-gray-400 transition-all group">
                            <svg class="text-green-500 group-hover:scale-110 transition-transform" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            <span class="font-semibold">WhatsApp</span>
                        </button>
                    </a>

                    <a href="https://instagram.com/umkm_store" target="_blank" rel="noopener noreferrer" class="block">
                        <button class="w-full flex items-center gap-3 h-14 px-4 border border-gray-300 rounded-md hover:shadow-md hover:border-gray-400 transition-all group">
                            <svg class="text-pink-500 group-hover:scale-110 transition-transform" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                            <span class="font-semibold">Instagram</span>
                        </button>
                    </a>

                    <a href="https://tiktok.com/@umkm_store" target="_blank" rel="noopener noreferrer" class="block">
                        <button class="w-full flex items-center gap-3 h-14 px-4 border border-gray-300 rounded-md hover:shadow-md hover:border-gray-400 transition-all group">
                            <svg class="text-gray-800 group-hover:scale-110 transition-transform" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                            </svg>
                            <span class="font-semibold">TikTok</span>
                        </button>
                    </a>
                </div>
            </div>

            <!-- Info Section -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold mb-4">Informasi</h3>
                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold mb-2 text-orange-500">Tentang UMKM Store</h4>
                        <p class="text-gray-600">
                            Kami adalah UMKM yang bergerak di bidang jasa dan produk berkualitas. 
                            Dengan pengalaman bertahun-tahun, kami berkomitmen memberikan layanan terbaik 
                            untuk kepuasan pelanggan.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-2 text-orange-500">Jam Operasional</h4>
                        <div class="space-y-1 text-sm text-gray-600">
                            <p>Senin - Jumat: 09:00 - 17:00</p>
                            <p>Sabtu: 09:00 - 15:00</p>
                            <p>Minggu: Tutup</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="mb-4">
                <h3 class="text-xl font-bold flex items-center gap-2">
                    <svg class="text-orange-500" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Lokasi Kami
                </h3>
            </div>
            <div class="aspect-video rounded-lg overflow-hidden mb-4">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3946.1002997836863!2d115.24395747477388!3d-8.48962829155169!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd23d004b2157f9%3A0x6c070782c0349538!2sMabes%20BENANG%20MERAH!5e0!3m2!1sid!2sid!4v1762179026246!5m2!1sid!2sid"
                    width="100%"
                    height="100%"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Lokasi Mabes BENANG MERAH"
                ></iframe>
            </div>
            <div class="p-4 bg-gray-100 rounded-lg">
                <p class="font-semibold mb-1">Alamat:</p>
                <p class="text-gray-600">
                    Jl. Kedewatan, Kecamatan Ubud, Kabupaten Gianyar, Bali
                </p>
            </div>
        </div>
    @endsection
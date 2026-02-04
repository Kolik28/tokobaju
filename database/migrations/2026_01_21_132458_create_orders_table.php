<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Struktur lengkap untuk fitur:
     * 1. Order Management (kategori, ukuran, variant, pesan, gambar)
     * 2. Pricing (harga total, DP, sisa)
     * 3. DP Payment (bukti, status, approval)
     * 4. Queue Management (posisi, nomor, status)
     * 5. Skip Queue (bukti, biaya, status, verification)
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // ==========================================
            // 1. INFORMASI PRODUK
            // ==========================================
            $table->string('category')->comment('Kategori: airbrush, polosan, fullset');
            $table->string('size')->comment('Ukuran dalam cm');
            $table->string('variant')->nullable()->comment('Varian produk jika ada');
            $table->text('message')->nullable()->comment('Pesan custom dari user');
            $table->string('image_path')->nullable()->comment('Path gambar desain/referensi');

            // ==========================================
            // 2. INFORMASI HARGA & PEMBAYARAN
            // ==========================================
            $table->decimal('total_price', 12, 2)->comment('Harga total pesanan');
            $table->decimal('dp_amount', 12, 2)->default(0)->comment('Jumlah DP yang sudah dibayar');
            $table->decimal('remaining_price', 12, 2)->default(0)->comment('Sisa harga yang perlu dibayar');
            
            $table->string('payment_proof')->nullable()->comment('Path bukti pembayaran DP');
            $table->enum('dp_status', ['orders', 'pending', 'approved', 'rejected'])->default('orders')
                ->comment('Status verifikasi DP: pending/approved/rejected');
            $table->timestamp('dp_approved_at')->nullable()->comment('Waktu admin approve DP');

            // ==========================================
            // 3. INFORMASI ANTRIAN
            // ==========================================
            $table->integer('queue_position')->index()->comment('Posisi antrian saat ini');
            $table->integer('queue_number')->comment('Nomor antrian (urutan pemesanan)');
            $table->enum('queue_status', ['waiting', 'processing', 'done', 'cancelled'])
                ->default('waiting')->comment('Status antrian');

            // ==========================================
            // 4. INFORMASI SKIP QUEUE (SALIP ANTRIAN)
            // ==========================================
            $table->boolean('is_priority')->default(false)
                ->comment('Flag: order ini sudah disalip antrian');
            
            $table->integer('priority_level')->default(0)
                ->comment('Berapa antrian yang disalip (jumlah)');
            
            $table->integer('skip_amount')->nullable()
                ->comment('Biaya salip antrian (Rp) = priority_level * 100000');
            
            $table->string('skip_proof')->nullable()
                ->comment('Path bukti pembayaran salip antrian');
            
            $table->enum('skip_status', ['pending', 'approved', 'rejected'])->nullable()
                ->comment('Status verifikasi salip: pending/approved/rejected');
            
            $table->timestamp('skip_verified_at')->nullable()
                ->comment('Waktu admin verify pembayaran salip');

            // ==========================================
            // 5. TIMESTAMPS
            // ==========================================
            $table->timestamps();

            // ==========================================
            // INDEXES untuk optimasi query
            // ==========================================
            // queue_position sudah di-index di atas
            // Tambahan indexes untuk query yang sering
            $table->index(['user_id', 'queue_status']);
            $table->index(['queue_status', 'queue_position']);
            $table->index('is_priority');
            $table->index('dp_status');
            $table->index('skip_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
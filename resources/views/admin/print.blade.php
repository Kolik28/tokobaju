<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pesanan #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            padding: 20px;
        }

        @media print {
            body {
                background-color: white;
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            border-bottom: 3px solid #1e40af;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #1e40af;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .order-id {
            background-color: #eff6ff;
            border-left: 4px solid #1e40af;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .order-id h2 {
            color: #1e40af;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .order-id p {
            color: #666;
            font-size: 13px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            background-color: #f3f4f6;
            color: #1e40af;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
            border-radius: 4px;
            border-left: 4px solid #1e40af;
        }

        .section-content {
            padding-left: 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #374151;
            width: 35%;
        }

        .info-value {
            color: #1f2937;
            width: 65%;
            text-align: right;
        }

        .info-value.text-left {
            text-align: left;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .payment-section {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
        }

        .payment-row.total {
            border-top: 2px solid #1e40af;
            border-bottom: 2px solid #1e40af;
            padding: 12px 0;
            font-weight: 600;
            color: #1e40af;
            margin: 10px 0;
        }

        .payment-row.remaining {
            color: #ea580c;
            font-weight: 600;
        }

        .payment-row.paid {
            color: #16a34a;
            font-weight: 600;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-waiting {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-processing {
            background-color: #dbeafe;
            color: #0c4a6e;
        }

        .status-done {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-cancelled {
            background-color: #fee2e2;
            color: #7f1d1d;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-approved {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-rejected {
            background-color: #fee2e2;
            color: #7f1d1d;
        }

        .product-image {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 4px;
            margin: 10px 0;
            border: 1px solid #e5e7eb;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #666;
            font-size: 12px;
        }

        .print-button {
            background-color: #1e40af;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .print-button:hover {
            background-color: #1e3a8a;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background-color: #1e40af;
            color: white;
        }

        .btn-primary:hover {
            background-color: #1e3a8a;
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
        }

        .btn-success {
            background-color: #16a34a;
            color: white;
        }

        .btn-success:hover {
            background-color: #15803d;
        }

        .attention-box {
            background-color: #fef3c7;
            border: 1px solid #fcd34d;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
        }

        .attention-box strong {
            color: #92400e;
        }

        @media print {
            .action-buttons,
            .print-button {
                display: none;
            }

            .container {
                box-shadow: none;
                padding: 20px;
            }
        }

        @media (max-width: 600px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 20px;
            }

            .header h1 {
                font-size: 20px;
            }

            .order-id h2 {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Action Buttons -->
        <div class="action-buttons no-print">
            <button class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print"></i> Cetak
            </button>
            <a href="{{ route('admin.download-image', $order->id) }}" class="btn btn-success" 
               @if(!$order->image_path) style="display:none;" @endif>
                <i class="fas fa-download"></i> Download Gambar
            </a>
        </div>

        <!-- Header -->
        <div class="header">
            <h1>Rincian Pesanan</h1>
            <p>Dokumen ini berisi informasi lengkap tentang pesanan Anda</p>
        </div>

        <!-- Order ID Section -->
        <div class="order-id">
            <h2>#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h2>
            <p>Dibuat pada: {{ $order->created_at->format('d F Y \p\u\k\u\l H:i') }}</p>
        </div>

        <!-- Status Section -->
        <div class="section">
            <div class="section-title">Status Pesanan</div>
            <div class="section-content">
                <div style="padding: 10px 0;">
                    <span class="status-badge status-{{ $order->queue_status }}">
                        {{ ucfirst($order->queue_status) }}
                    </span>
                    @if($order->dp_status !== 'orders')
                        <span class="status-badge status-{{ $order->dp_status }}" style="margin-left: 10px;">
                            DP: {{ ucfirst($order->dp_status) }}
                        </span>
                    @endif
                </div>
                <div style="padding: 10px 0; color: #666; font-size: 14px;">
                    Antrian: <strong>{{ $order->queue_position }} dari {{ $order->queue_number }}</strong>
                    @if($order->is_priority)
                        <span style="margin-left: 10px; color: #ea580c;">⭐ Priority</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="section">
            <div class="section-title">Informasi Pelanggan</div>
            <div class="section-content">
                <div class="info-row">
                    <span class="info-label">Nama:</span>
                    <span class="info-value text-left">{{ $order->user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value text-left">{{ $order->user->email ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">WhatsApp:</span>
                    <span class="info-value text-left">{{ $order->user->whatsapp }}</span>
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="section">
            <div class="section-title">Detail Produk</div>
            <div class="section-content">
                <div class="info-row">
                    <span class="info-label">Kategori:</span>
                    <span class="info-value text-left capitalize">{{ $order->category }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ukuran:</span>
                    <span class="info-value text-left">{{ $order->size }}</span>
                </div>
                @if($order->variant)
                    <div class="info-row">
                        <span class="info-label">Varian:</span>
                        <span class="info-value text-left">{{ $order->variant }}</span>
                    </div>
                @endif
                @if($order->description)
                    <div class="section" style="margin-top: 15px;">
                        <strong style="color: #374151;">Deskripsi:</strong>
                        <p style="margin-top: 8px; color: #1f2937; line-height: 1.6;">
                            {{ $order->description }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Image -->
        @if($order->image_path)
            <div class="section">
                <div class="section-title">Gambar Pesanan</div>
                <div class="section-content">
                    <img src="{{ Storage::url($order->image_path) }}" alt="Pesanan" class="product-image">
                </div>
            </div>
        @endif

        <!-- Payment Info -->
        <div class="section">
            <div class="section-title">Informasi Pembayaran</div>
            <div class="payment-section">
                <div class="payment-row">
                    <span>Total Harga:</span>
                    <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                </div>
                <div class="payment-row paid">
                    <span>DP Dibayar:</span>
                    <strong>Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</strong>
                </div>

                @if($order->remaining_price > 0)
                    <div class="payment-row total">
                        <span>Sisa Pembayaran:</span>
                        <strong>Rp {{ number_format($order->remaining_price, 0, ',', '.') }}</strong>
                    </div>
                    <div class="attention-box">
                        <strong>⚠️ Perhatian:</strong> Masih ada sisa pembayaran yang harus diselesaikan sebelum pesanan diserahkan.
                    </div>
                @else
                    <div class="payment-row" style="background-color: #dcfce7; color: #16a34a; padding: 10px; border-radius: 4px;">
                        <strong>✓ Pembayaran Lunas</strong>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Dokumen ini dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
            <p>Silakan hubungi kami melalui WhatsApp jika ada pertanyaan atau perubahan pesanan</p>
        </div>
    </div>

    <!-- Font Awesome for icons (optional) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>
</html>
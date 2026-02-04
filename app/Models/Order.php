<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    /**
     * ======================================================================
     * TABLE & MASS ASSIGNMENT
     * ======================================================================
     */

    protected $table = 'orders';

    /**
     * Fillable attributes untuk mass assignment
     * Semua field yang bisa diisi via create() atau update()
     */
    protected $fillable = [
        // Informasi Produk
        'user_id',
        'category',
        'size',
        'variant',
        'message',
        'image_path',

        // Informasi Harga & Pembayaran
        'total_price',
        'dp_amount',
        'remaining_price',
        'payment_proof',
        'dp_status',
        'dp_approved_at',

        // Informasi Antrian
        'queue_position',
        'queue_number',
        'queue_status',

        // Informasi Skip Queue
        'is_priority',
        'priority_level',
        'skip_amount',
        'skip_proof',
        'skip_status',
        'skip_verified_at',
    ];

    /**
     * ======================================================================
     * ATTRIBUTE CASTING & TYPE CASTING
     * ======================================================================
     */

    protected $casts = [
        // Numeric fields
        'total_price' => 'float',
        'dp_amount' => 'float',
        'remaining_price' => 'float',
        'skip_amount' => 'integer',
        'queue_position' => 'integer',
        'queue_number' => 'integer',
        'priority_level' => 'integer',

        // Boolean fields
        'is_priority' => 'boolean',

        // Datetime fields
        'dp_approved_at' => 'datetime',
        'skip_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * ======================================================================
     * RELATIONSHIPS
     * ======================================================================
     */

    /**
     * Relationship dengan User
     * Satu order milik satu user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ======================================================================
     * ACCESSORS & MUTATORS
     * ======================================================================
     */

    /**
     * Get formatted price (untuk display di view)
     * 
     * Usage: $order->formatted_total_price
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    /**
     * Get formatted DP amount
     * 
     * Usage: $order->formatted_dp_amount
     */
    public function getFormattedDpAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->dp_amount, 0, ',', '.');
    }

    /**
     * Get formatted remaining price
     * 
     * Usage: $order->formatted_remaining_price
     */
    public function getFormattedRemainingPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->remaining_price, 0, ',', '.');
    }

    /**
     * Get formatted skip amount
     * 
     * Usage: $order->formatted_skip_amount
     */
    public function getFormattedSkipAmountAttribute(): string
    {
        if ($this->skip_amount === null) {
            return 'Rp 0';
        }
        return 'Rp ' . number_format($this->skip_amount, 0, ',', '.');
    }

    /**
     * ======================================================================
     * SCOPES
     * ======================================================================
     */

    /**
     * Scope: Filter order by queue status
     * 
     * Usage: Order::waiting()->get()
     */
    public function scopeWaiting($query)
    {
        return $query->where('queue_status', 'waiting');
    }

    /**
     * Scope: Filter order by status processing
     * 
     * Usage: Order::processing()->get()
     */
    public function scopeProcessing($query)
    {
        return $query->where('queue_status', 'processing');
    }

    /**
     * Scope: Filter order by status done
     * 
     * Usage: Order::done()->get()
     */
    public function scopeDone($query)
    {
        return $query->where('queue_status', 'done');
    }

    /**
     * Scope: Filter order yang sudah priority (salip antrian)
     * 
     * Usage: Order::priority()->get()
     */
    public function scopePriority($query)
    {
        return $query->where('is_priority', true);
    }

    /**
     * Scope: Filter order dengan DP pending
     * 
     * Usage: Order::dpPending()->get()
     */
    public function scopeDpPending($query)
    {
        return $query->where('dp_status', 'pending');
    }

    /**
     * Scope: Filter order dengan DP approved
     * 
     * Usage: Order::dpApproved()->get()
     */
    public function scopeDpApproved($query)
    {
        return $query->where('dp_status', 'approved');
    }

    /**
     * Scope: Filter order dengan skip pending
     * 
     * Usage: Order::skipPending()->get()
     */
    public function scopeSkipPending($query)
    {
        return $query->where('skip_status', 'pending');
    }

    /**
     * Scope: Filter order yang belum DP penuh
     * 
     * Usage: Order::notFullDp()->get()
     */
    public function scopeNotFullDp($query)
    {
        return $query->whereRaw('dp_amount < total_price');
    }

    /**
     * Scope: Filter order yang sudah DP penuh
     * 
     * Usage: Order::fullDp()->get()
     */
    public function scopeFullDp($query)
    {
        return $query->whereRaw('dp_amount >= total_price');
    }

    /**
     * ======================================================================
     * METHODS & HELPERS
     * ======================================================================
     */

    /**
     * Check apakah DP sudah di-approve
     * 
     * Usage: if ($order->isDpApproved()) { ... }
     */
    public function isDpApproved(): bool
    {
        return $this->dp_status === 'approved';
    }

    /**
     * Check apakah DP masih pending
     * 
     * Usage: if ($order->isDpPending()) { ... }
     */
    public function isDpPending(): bool
    {
        return $this->dp_status === 'pending';
    }

    /**
     * Check apakah DP sudah ditolak
     * 
     * Usage: if ($order->isDpRejected()) { ... }
     */
    public function isDpRejected(): bool
    {
        return $this->dp_status === 'rejected';
    }

    /**
     * Check apakah skip queue sudah diverifikasi
     * 
     * Usage: if ($order->isSkipVerified()) { ... }
     */
    public function isSkipVerified(): bool
    {
        return $this->skip_status === 'approved';
    }

    /**
     * Check apakah skip queue masih pending
     * 
     * Usage: if ($order->isSkipPending()) { ... }
     */
    public function isSkipPending(): bool
    {
        return $this->skip_status === 'pending';
    }

    /**
     * Check apakah order masih waiting
     * 
     * Usage: if ($order->isWaiting()) { ... }
     */
    public function isWaiting(): bool
    {
        return $this->queue_status === 'waiting';
    }

    /**
     * Check apakah order sudah selesai
     * 
     * Usage: if ($order->isDone()) { ... }
     */
    public function isDone(): bool
    {
        return $this->queue_status === 'done';
    }

    /**
     * Check apakah order sudah dibatalkan
     * 
     * Usage: if ($order->isCancelled()) { ... }
     */
    public function isCancelled(): bool
    {
        return $this->queue_status === 'cancelled';
    }

    /**
     * Get sisa harga yang perlu dibayar (dalam integer Rp)
     * 
     * Usage: $remainingInt = $order->getRemainingInt()
     */
    public function getRemainingInt(): int
    {
        return (int) $this->remaining_price;
    }

    /**
     * Check apakah semua pembayaran sudah terlunasi
     * 
     * Usage: if ($order->isFullyPaid()) { ... }
     */
    public function isFullyPaid(): bool
    {
        return $this->remaining_price <= 0;
    }

    /**
     * Get status dalam bahasa Indonesia
     * 
     * Usage: echo $order->getQueueStatusLabel()
     */
    public function getQueueStatusLabel(): string
    {
        return match($this->queue_status) {
            'waiting' => 'Menunggu',
            'processing' => 'Sedang Dikerjakan',
            'done' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Unknown'
        };
    }

    /**
     * Get DP status dalam bahasa Indonesia
     * 
     * Usage: echo $order->getDpStatusLabel()
     */
    public function getDpStatusLabel(): string
    {
        return match($this->dp_status) {
            'pending' => 'Menunggu Verifikasi',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Unknown'
        };
    }

    /**
     * Get skip status dalam bahasa Indonesia
     * 
     * Usage: echo $order->getSkipStatusLabel()
     */
    public function getSkipStatusLabel(): string
    {
        if ($this->skip_status === null) {
            return 'Tidak Ada Skip';
        }

        return match($this->skip_status) {
            'pending' => 'Menunggu Verifikasi',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Unknown'
        };
    }

    /**
     * Get kategori dalam bahasa Indonesia
     * 
     * Usage: echo $order->getCategoryLabel()
     */
    public function getCategoryLabel(): string
    {
        return match($this->category) {
            'airbrush' => 'Airbrush',
            'polosan' => 'Polosan',
            'fullset' => 'Fullset',
            default => 'Unknown'
        };
    }
}
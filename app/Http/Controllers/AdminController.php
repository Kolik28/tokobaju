<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = DB::table('users')->count();
        $totalOrders = DB::table('orders')->count();
        $pendingDpCount = DB::table('orders')
            ->where('dp_status', 'pending')
            ->where('queue_status', 'waiting')
            ->count();
        $pendingSkipCount = DB::table('orders')
            ->where('is_priority', true)
            ->whereNull('skip_verified_at')
            ->count();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalOrders' => $totalOrders,
            'pendingDpCount' => $pendingDpCount,
            'pendingSkipCount' => $pendingSkipCount,
        ]);
    }

    public function users()
    {
        $users = DB::table('users')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users', ['users' => $users]);
    }

    public function orders()
    {
        $orders = Order::with('user')
            ->orderBy('queue_position', 'asc')
            ->get();

        return view('admin.orders', ['orders' => $orders]);
    }

    public function getDetail($id)
    {
        try {
            $order = Order::with('user')->findOrFail($id);

            // Format data untuk response
            return response()->json([
                'id' => $order->id,
                'user' => [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                    'whatsapp' => $order->user->whatsapp,
                ],
                'category' => $order->category,
                'size' => $order->size,
                'variant' => $order->variant,
                'description' => $order->description,
                'image_path' => $order->image_path,
                'total_price' => $order->total_price,
                'dp_amount' => $order->dp_amount,
                'remaining_price' => $order->total_price - $order->dp_amount,
                'queue_status' => $order->queue_status,
                'queue_position' => $order->queue_position,
                'queue_number' => $order->queue_number,
                'is_priority' => (bool)$order->is_priority,
                'dp_status' => $order->dp_status,
                'created_at' => $order->created_at->toIso8601String(),
                'updated_at' => $order->updated_at->toIso8601String(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Pesanan tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Print order detail
     * @param int $id Order ID
     */
    public function print($id)
    {
        $order = Order::with('user')->findOrFail($id);
        return view('admin.print', compact('order'));
    }

    /**
     * Download order image
     * @param int $id Order ID
     */
    public function downloadImage($id)
    {
        try {
            $order = Order::findOrFail($id);

            if (!$order->image_path || !Storage::exists($order->image_path)) {
                return redirect()->back()->with('error', 'Gambar pesanan tidak ditemukan');
            }

            // Generate filename
            $filename = 'pesanan-' . str_pad($order->id, 5, '0', STR_PAD_LEFT) . '.jpg';

            return Storage::download($order->image_path, $filename);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mendownload gambar');
        }
    }

    /**
     * Export orders to Excel
     */
    public function export()
    {
        $orders = Order::with('user')->get();

        // Atau alternatif tanpa package:
        return $this->exportToCSV($orders);
    }

    /**
     * Export to CSV (alternatif tanpa package)
     */
    private function exportToCSV($orders)
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=pesanan.csv',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'Order ID',
                'Tanggal',
                'Nama Pelanggan',
                'WhatsApp',
                'Kategori',
                'Ukuran',
                'Varian',
                'Total Harga',
                'DP Dibayar',
                'Sisa Pembayaran',
                'Status Antrian',
                'Status DP',
                'Antrian',
            ]);

            // Data rows
            foreach ($orders as $order) {
                fputcsv($file, [
                    str_pad($order->id, 5, '0', STR_PAD_LEFT),
                    $order->created_at->format('d-m-Y H:i'),
                    $order->user->name,
                    $order->user->whatsapp,
                    $order->category,
                    $order->size,
                    $order->variant ?? '-',
                    $order->total_price,
                    $order->dp_amount,
                    $order->total_price - $order->dp_amount,
                    ucfirst($order->queue_status),
                    ucfirst($order->dp_status),
                    $order->queue_position . '/' . $order->queue_number,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        DB::transaction(function () use ($request, $id) {

            $order = Order::findOrFail($id);
            $newStatus = $request->input('queue_status');

            // Jika jadi PROCESSING
            if ($newStatus === 'processing') {

                // Set order ini jadi 0
                $order->update([
                    'queue_status'   => 'processing',
                    'queue_position' => 0,
                ]);

                // Ambil semua order WAITING, urutkan
                $waitingOrders = Order::where('queue_status', 'waiting')
                    ->orderBy('queue_position')
                    ->get();

                // Re-index: mulai dari 1
                $position = 1;
                foreach ($waitingOrders as $waiting) {
                    $waiting->update([
                        'queue_position' => $position++
                    ]);
                }
            } else {
                // Update status biasa
                $order->update([
                    'queue_status' => $newStatus
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Antrian berhasil dirapikan'
        ]);
    }


    /**
     * Delete order
     */
    public function delete($id)
    {
        try {
            $order = Order::findOrFail($id);

            // Delete image if exists
            if ($order->image_path && Storage::exists($order->image_path)) {
                Storage::delete($order->image_path);
            }

            $order->delete();

            return redirect()->back()->with('success', 'Pesanan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus pesanan');
        }
    }

    public function skipQueueRequests()
    {
        // Pending requests
        $pendingRequests = Order::where('is_priority', true)
            ->where('skip_verified_at', null)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Approved requests
        $approvedRequests = Order::where('is_priority', true)
            ->whereNotNull('skip_verified_at')
            ->where('queue_status', 'waiting')
            ->with('user')
            ->orderBy('skip_verified_at', 'desc')
            ->get();

        // Rejected requests
        $rejectedRequests = Order::where('is_priority', false)
            ->whereNotNull('skip_verified_at')
            ->where('queue_status', 'waiting')
            ->with('user')
            ->orderBy('skip_verified_at', 'desc')
            ->get();

        return view('admin.skip-queue-requests', [
            'pendingRequests' => $pendingRequests,
            'approvedRequests' => $approvedRequests,
            'rejectedRequests' => $rejectedRequests,
            'pendingCount' => $pendingRequests->count(),
            'approvedCount' => $approvedRequests->count(),
            'rejectedCount' => $rejectedRequests->count(),
        ]);
    }

    public function approveSkipQueue(Request $request)
    {
        $message = '';

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'skip_status' => 'required|in:approved,rejected',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Ambil order dengan lock
                $order = Order::lockForUpdate()->findOrFail($request->order_id);

                // Validasi order memiliki skip request
                if (!$order->is_priority || is_null($order->priority_level)) {
                    throw new \Exception('Order ini tidak memiliki permintaan skip antrian');
                }

                if ($request->skip_status === 'approved') {
                    $current = $order->queue_position;
                    $skip = $order->priority_level;

                    // Hitung posisi baru
                    $newPosition = max(1, $current - $skip);

                    // Geser antrean lain yang berada di atas posisi baru
                    Order::where('queue_position', '>=', $newPosition)
                        ->where('queue_position', '<', $current)
                        ->where('queue_status', 'waiting')
                        ->where('id', '!=', $order->id)
                        ->increment('queue_position');

                    // Update order dengan posisi baru
                    $order->update([
                        'queue_position' => $newPosition,
                        'skip_verified_at' => now(),
                        'skip_status' => 'approved'
                    ]);

                    $message = 'Permintaan skip antrian disetujui. Posisi antrian telah diperbarui.';
                } else {
                    // Rejected: kembalikan ke posisi normal, hapus priority
                    $order->update([
                        'is_priority' => false,
                        'priority_level' => 0,
                        'skip_proof' => null,
                        'skip_amount' => null,
                        'skip_verified_at' => now()
                    ]);

                    $message = 'Permintaan skip antrian ditolak. Status pengembalian ke normal.';
                }
            });

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }


    public function dpVerificationList()
    {
        $pendingDp = Order::where('dp_status', 'pending')
            ->whereIn('queue_status', ['waiting', 'processing'])
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $verifiedDp = Order::where('dp_status', 'approved')
            ->whereIn('queue_status', ['waiting', 'processing'])
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->get();

        $rejectedDp = Order::where('dp_status', 'rejected')
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.verify-dp', [
            'pendingDp' => $pendingDp,
            'verifiedDp' => $verifiedDp,
            'rejectedDp' => $rejectedDp,
            'pendingCount' => $pendingDp->count(),
            'verifiedCount' => $verifiedDp->count(),
            'rejectedCount' => $rejectedDp->count(),
        ]);
    }

    public function verifyDp(Request $request)
    {
        $message = '';

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|in:approved,rejected',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $order = Order::lockForUpdate()->findOrFail($request->order_id);

                // Validasi status pending
                if ($order->dp_status !== 'pending') {
                    throw new \Exception('Status DP sudah diproses sebelumnya');
                }

                if ($request->status === 'approved') {

                    // Update order status
                    $order->update([
                        'dp_status' => 'approved',
                        'remaining_price' => $order->total_price - $order->dp_amount,
                        'dp_approved_at' => now(),
                    ]);

                    $message = 'DP berhasil diverifikasi. Customer bisa lanjut transaksi.';
                } else {
                    // Update order status to rejected
                    $order->update([
                        'dp_status' => 'rejected',
                        'dp_approved_at' => now(),
                    ]);

                    $message = 'DP ditolak. Notifikasi sudah dikirim ke customer.';
                }
            });

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal verifikasi DP: ' . $e->getMessage());
        }
    }
}

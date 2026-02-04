<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * =========================
     * DASHBOARD WITH ORDER SELECTION
     * =========================
     */

    public function home(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'nullable|integer|exists:orders,id'
        ]);

        $user = Auth::user();

        $userOrders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $selectedOrderId = $validated['order_id'] ?? $userOrders->first()->id ?? null;
        $selectedOrder = null;

        if ($selectedOrderId) {
            $selectedOrder = Order::where('id', $selectedOrderId)
                ->where('user_id', Auth::id())
                ->first();

            if (!$selectedOrder) {
                return redirect()->route('home')->with('error', 'Order tidak ditemukan');
            }
        }

        $queues = Order::where('queue_status', 'waiting')
            ->orderBy('queue_position', 'asc')
            ->take(5)
            ->with('user')
            ->get();

        return view('home', compact('user', 'userOrders', 'selectedOrder', 'queues'));
    }

    /**
     * =========================
     * DAFTAR PESANAN
     * =========================
     */


    public function index()
    {
        $orders = Order::orderBy('queue_position')->get();
        return view('orders', compact('orders'));
    }

    /**
     * =========================
     * BUAT PESANAN BARU
     * =========================
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'size' => 'required',
            'variant' => 'nullable',
            'message' => 'nullable',
            'image' => 'nullable|image',
            'amount' => 'required|numeric',
            'dp_amount' => 'nullable|numeric|min:0'
        ]);

        DB::transaction(function () use ($request) {

            $lastQueue = Order::max('queue_position') ?? 0;
            $queueNumber = Order::max('queue_number') ?? 0;

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('orders', 'public');
            }

            $totalPrice = $this->calculatePrice(
                $request['category'],
                $request['size'],
                $request['variant']
            );

            Order::create([
                'user_id' => Auth::id(),
                'category' => $request->category,
                'size' => $request->size,
                'variant' => $request->variant,
                'message' => $request->message,
                'image_path' => $imagePath,

                'total_price' => $totalPrice,
                'dp_amount' => $request->dp_amount ?? 0,
                'remaining_price' => $totalPrice - ($request->dp_amount ?? 0),

                'queue_position' => $lastQueue + 1,
                'queue_number' => $queueNumber + 1,
                'queue_status' => 'waiting'
            ]);
        });

        return redirect()->route('home')->with('success', 'Pesanan berhasil dibuat!');
    }


    // ============================================
    // Halaman Salip Antrian - DENGAN PILIH ORDER
    // ============================================
    /**
     * Tampilkan halaman form pilih order untuk salip antrian
     */
    public function showSalipQueue()
    {
        // Ambil semua order milik user yang masih waiting
        $userOrders = Order::where('user_id', Auth::id())
            ->where('queue_status', 'waiting')
            ->orderBy('queue_position', 'asc')
            ->get();

        if ($userOrders->isEmpty()) {
            return redirect()->route('orders.index')
                ->with('error', 'Anda tidak memiliki pesanan yang aktif');
        }

        return view('skipqueue', compact('userOrders'));
    }

    /**
     * Proses salip antrian dengan order_id yang dipilih
     */
    public function skipQueue(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'jumlah_salip' => 'required|integer|min:1',
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Upload bukti pembayaran
                $proofPath = $request->file('image_url')->store('salip-bukti', 'public');

                // Ambil order dengan validasi ownership
                $order = Order::where('user_id', Auth::id())
                    ->where('id', $request->order_id)
                    ->where('queue_status', 'waiting')
                    ->lockForUpdate()
                    ->firstOrFail();

                // Check queue_status
                if ($order->queue_status !== 'waiting') {
                    throw new \Exception('Pesanan sudah diproses');
                }

                // Simpan data skip tanpa menggeser antrian (menunggu approval admin)
                $order->update([
                    'is_priority' => true,
                    'priority_level' => $request->jumlah_salip,
                    'skip_proof' => $proofPath,
                    'skip_amount' => $request->jumlah_salip * 100000,
                    'skip_verified_at' => null
                ]);
            });

            return back()->with('success', 'Bukti pembayaran salip antrian berhasil diunggah. Tunggu verifikasi admin.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses permintaan: ' . $e->getMessage());
        }
    }


    // ============================================
    // Halaman Down Payment - DENGAN PILIH ORDER
    // ============================================
    /**
     * Tampilkan halaman form DP dengan pilih order
     */
    public function showDownPayment()
    {
        // Ambil semua order milik user yang masih waiting dan belum DP penuh
        $userOrders = Order::where('user_id', Auth::id())
            ->whereIn('queue_status', ['waiting', 'processing'])
            ->where(function ($query) {
                // Pesanan yang belum DP sama sekali atau DP belum penuh
                $query->whereNull('dp_amount')
                    ->orWhere('dp_amount', 0)
                    ->orWhere('dp_amount', '<', DB::raw('total_price'));
            })
            ->orderBy('queue_position', 'asc')
            ->get();

        if ($userOrders->isEmpty()) {
            return redirect()->route('orders.index')
                ->with('error', 'Anda tidak memiliki pesanan yang memerlukan DP');
        }

        return view('downpayment', compact('userOrders'));
    }

    /**
     * Proses upload bukti DP dengan order_id yang dipilih
     */
    public function uploadDp(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:1',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        DB::transaction(function () use ($request) {

            // Ambil order dengan validasi ownership
            $order = Order::where('user_id', Auth::id())
                ->where('id', $request->order_id)
                ->whereIn('queue_status', ['waiting', 'processing'])
                ->lockForUpdate()
                ->firstOrFail();

            $currentDp = $order->dp_amount ?? 0;
            $newTotalDp = $currentDp + $request->amount;

            // Validasi: DP tidak boleh lebih dari total harga
            if ($newTotalDp > $order->total_price) {
                throw new \Exception("Total DP tidak boleh melebihi harga pesanan");
            }

            // Upload bukti pembayaran
            $path = $request->file('payment_proof')->store('dp', 'public');

            // Hitung remaining price
            $remainingPrice = $order->total_price;

            // Update order
            $order->update([
                'payment_proof' => $path,
                'dp_amount' => $newTotalDp,
                'remaining_price' => $remainingPrice,
                'dp_status' => 'pending'
            ]);
        });

        return back()->with('success', 'Bukti DP berhasil diupload. Tunggu verifikasi admin.');
    }


    // ============================================
    // Hitung Harga (Logic Utama)
    // ============================================
    public function calculatePrice($category, $size, $variant = null)
    {
        $total = 0;

        if ($category == 'airbrush') {
            $baseRate = ($size > 250) ? 150000 : 130000;
            $total = ($size / 100) * $baseRate;
            if ($variant == 'tambahan kain') {
                $total += ($size > 180) ? 20000 : 10000;
            }
        } elseif ($category == 'polosan') {
            $total = ($size / 100) * 120000;
        } elseif ($category == 'fullset') {
            $baseRate = ($size > 250) ? 340000 : 300000;
            $total = ($size / 100) * $baseRate;
            if ($variant == 'welcro') {
                $total += ($size > 180) ? 30000 : 10000;
            }
        }

        return round($total, 2);
    }

    // ============================================
    // API untuk real-time price calculation (AJAX)
    // ============================================
    public function calculatePriceApi(Request $request)
    {
        $category = $request->query('category');
        $size = $request->query('size');
        $variant = $request->query('variant');

        $price = $this->calculatePrice($category, $size, $variant);

        return response()->json([
            'total_price' => $price,
            'formatted_price' => 'Rp ' . number_format($price, 0, ',', '.'),
        ]);
    }


    public function about()
    {
        return view('about');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::with(['user', 'items'])
            ->when($request->filled('status'), fn ($q) => $q->where('status',$request->string('status')->toString()))
            ->latest('ordered_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items', 'validator']);

        return view('admin.orders.show', compact('order'));
    }

    public function validateOrder(Request $request, Order$order): RedirectResponse
    {
        abort_unless($order->status === 'pending', 422, 'Hanya pesanan pending yang dapat divalidasi.');

        $order->update([
            'status' => 'validated',
            'validated_by' => $request->user()->id,
            'validated_at' => now(),
        ]);

        return back()->with('success', "Pesanan {$order->code} berhasil divalidasi.");
    }

    public function updateStatus(Request $request, Order$order): RedirectResponse
    {
        $data =$request->validate([
            'status' => ['required', Rule::in(['processed', 'shipped', 'completed'])],
        ]);

        $allowedNext = [
            'validated' => 'processed',
            'processed' => 'shipped',
            'shipped' => 'completed',
        ];

        $expectedNext = $allowedNext[$order->status] ?? null;

        if ($expectedNext !==$data['status']) {
            return back()->with('error', "Transisi status dari {$order->status} ke {$data['status']} tidak diizinkan.");
        }

        $order->update([
            'status' => $data['status'],
        ]);

        return back()->with('success', "Status pesanan {$order->code} diubah menjadi {$data['status']}.");
    }

    public function cancel(Request $request, Order$order): RedirectResponse
    {
        if (in_array($order->status, ['completed', 'shipped', 'cancelled'], true)) {
            return back()->with('error', "Pesanan berstatus {$order->status} tidak dapat dibatalkan.");
        }

        DB::transaction(function () use ($request,$order) {
            $lockedOrder = Order::with('items')->whereKey($order->id)->lockForUpdate()->firstOrFail();

            if (in_array($lockedOrder->status, ['completed', 'shipped', 'cancelled'], true)) {
                return;
            }

            $productIds =$lockedOrder->items->pluck('product_id')->filter()->unique()->values();
            $lockedProducts = Product::whereIn('id',$productIds)->lockForUpdate()->get()->keyBy('id');

            foreach ($lockedOrder->items as$item) {
                if (! $item->product_id) {
                    continue;
                }

                $product = $lockedProducts->get($item->product_id);
                if (! $product) {
                    continue;
                }

                $product->increment('stock',$item->quantity);

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $request->user()->id,
                    'type' => 'IN',
                    'quantity' => $item->quantity,
                    'reference' => 'CANCEL-'.$lockedOrder->code,
                    'note' => 'Pembatalan pesanan '.$lockedOrder->code,
                    'movement_at' => now(),
                ]);
            }

            $lockedOrder->update([
                'status' => 'cancelled',
            ]);
        });

        return back()->with('success', "Pesanan {$order->code} berhasil dibatalkan dan stok dikembalikan.");
    }
}
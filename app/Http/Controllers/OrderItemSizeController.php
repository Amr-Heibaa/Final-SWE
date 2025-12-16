<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\OrderItemSize;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderItemSizeController extends Controller
{
    /**
     * List all size quantities for a specific order item.
     * Route: GET /admin/order-items/{orderItem}/sizes
     */
    public function index(OrderItem $orderItem)
    {
        $orderItem->load(['itemSizes.size', 'order']);

        return view('order-item-sizes.index', [
            'orderItem'  => $orderItem,
            'itemSizes'  => $orderItem->itemSizes,
        ]);
    }

    /**
     * Show a specific size quantity for an order item.
     * Route: GET /admin/order-items/{orderItem}/sizes/{orderItemSize}
     */
    public function show(OrderItem $orderItem, OrderItemSize $orderItemSize)
    {
        // Ensure this size really belongs to this item
        if ($orderItemSize->order_item_id !== $orderItem->id) {
            abort(404);
        }

        $orderItemSize->load('size', 'orderItem.order');

        return view('order-item-sizes.show', [
            'orderItem'     => $orderItem,
            'orderItemSize' => $orderItemSize,
        ]);
    }

    /**
     * Store a new size + quantity for an order item.
     * Route: POST /admin/order-items/{orderItem}/sizes
     */
    public function store(Request $request, OrderItem $orderItem)
    {
        $data = $request->validate([
            'size_id'  => ['required', 'exists:sizes,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($orderItem, $data) {
            // Create the record
            OrderItemSize::create([
                'order_item_id' => $orderItem->id,
                'size_id'       => $data['size_id'],
                'quantity'      => $data['quantity'],
            ]);

            // Recalculate order total
            $this->recalculateOrderTotal($orderItem);
        });

        return redirect()
            ->back()
            ->with('success', 'Size quantity added successfully.');
    }

    /**
     * Update quantity (or size) of an existing record.
     * Route: PUT/PATCH /admin/order-items/{orderItem}/sizes/{orderItemSize}
     */
    public function update(Request $request, OrderItem $orderItem, OrderItemSize $orderItemSize)
    {
        if ($orderItemSize->order_item_id !== $orderItem->id) {
            abort(404);
        }

        $data = $request->validate([
            'size_id'  => ['required', 'exists:sizes,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($orderItem, $orderItemSize, $data) {
            $orderItemSize->update($data);

            $this->recalculateOrderTotal($orderItem);
        });

        return redirect()
            ->back()
            ->with('success', 'Size quantity updated successfully.');
    }

    /**
     * Delete a size/quantity for an order item.
     * Route: DELETE /admin/order-items/{orderItem}/sizes/{orderItemSize}
     */
    public function destroy(OrderItem $orderItem, OrderItemSize $orderItemSize)
    {
        if ($orderItemSize->order_item_id !== $orderItem->id) {
            abort(404);
        }

        DB::transaction(function () use ($orderItem, $orderItemSize) {
            $orderItemSize->delete();

            $this->recalculateOrderTotal($orderItem);
        });

        return redirect()
            ->back()
            ->with('success', 'Size quantity removed successfully.');
    }

    /**
     * Helper: recalculate the parent order total price.
     */
    protected function recalculateOrderTotal(OrderItem $orderItem): void
    {
        $order = $orderItem->order()->with('items.itemSizes')->first();

        $total = 0;

        foreach ($order->items as $item) {
            foreach ($item->itemSizes as $itemSize) {
                $total += $item->single_price * $itemSize->quantity;
            }
        }

        $order->update(['total_price' => $total]);
    }
}

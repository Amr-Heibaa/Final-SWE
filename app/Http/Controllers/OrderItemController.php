<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{

    // Optional: list items for one order
    public function index(Order $order)
    {
        $order->load('items');   // no sizes

        return response()->json([
            'items' => $order->items,
        ]);
    }

    // Create a new item for an existing order (no sizes here)
    public function store(Request $request, Order $order)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'fabric_name'  => 'nullable|string|max:255',
            'has_printing' => 'boolean',
            'description'  => 'nullable|string',
            'single_price' => 'required|numeric|min:0',
        ]);

        $item = $order->items()->create([
            'name'         => $data['name'],
            'fabric_name'  => $data['fabric_name'] ?? null,
            'has_printing' => $data['has_printing'] ?? false,
            'description'  => $data['description'] ?? null,
            'single_price' => $data['single_price'],
        ]);

        return response()->json([
            'message' => 'Item created.',
            'item'    => $item,
        ]);
    }

    // Update item fields only
    public function update(Request $request, Order $order, OrderItem $orderItem)
    {
        if ($orderItem->order_id !== $order->id) {
            abort(404);
        }

        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'fabric_name'  => 'nullable|string|max:255',
            'has_printing' => 'boolean',
            'description'  => 'nullable|string',
            'single_price' => 'required|numeric|min:0',
        ]);

        $orderItem->update([
            'name'         => $data['name'],
            'fabric_name'  => $data['fabric_name'] ?? null,
            'has_printing' => $data['has_printing'] ?? false,
            'description'  => $data['description'] ?? null,
            'single_price' => $data['single_price'],
        ]);

        return response()->json([
            'message' => 'Item updated.',
            'item'    => $orderItem,
        ]);
    }

    // Delete the item only; sizes handled elsewhere (cascade/other controller)
    public function destroy(Order $order, OrderItem $orderItem)
    {
        if ($orderItem->order_id !== $order->id) {
            abort(404);
        }

        $orderItem->delete();

        return response()->json([
            'message' => 'Item deleted.',
        ]);
    }
}

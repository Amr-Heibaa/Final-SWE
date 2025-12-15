<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemSize;
use App\Models\Meeting;
use App\Models\User;
use App\Models\Size;
use App\Enums\OrderPhaseEnum;
use App\Enums\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;



class OrderController extends Controller
{
    /**
     * Constructor - Apply middleware for role-based access
     */
   public function __construct()
{
    $this->middleware(function ($request, $next) {

        // اسم الروت الحالي
        $routeName = optional($request->route())->getName();

        // اسمح للـ customer بصفحاتهم فقط
        $allowedForCustomer = ['orders.my-orders', 'orders.my-order'];

        if (Auth::check() && Auth::user()->role === RoleEnum::CUSTOMER) {
            if (!in_array($routeName, $allowedForCustomer)) {
                return redirect()->route('meetings.index')
                    ->with('error', 'You do not have permission to access order management.');
            }
        }

        return $next($request);
    });
}


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // Base query with relationships
        $orders = Order::with(['customer', 'meeting', 'creator'])->latest();

        // Apply filters if provided
        if ($request->filled('customer_id')) {
            $orders->where('customer_id', $request->customer_id);
        }

        if ($request->filled('meeting_id')) {
            $orders->where('meeting_id', $request->meeting_id);
        }

        if ($request->filled('phase')) {
            $orders->where('current_phase', $request->phase);
        }
        if ($request->has('requires_printing')) {
            $orders->where('requires_printing', $request->boolean('requires_printing'));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $orders->where(function ($query) use ($search) {
                $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // Paginate results
        $orders = $orders->paginate(20);

        // Get phases for filter dropdown
        $phases = OrderPhaseEnum::forDropdown(true);

        // Get customers for filter dropdown (only customers, not admins)
        $customers = User::where('role', RoleEnum::CUSTOMER->value)->get(['id', 'name', 'email']);

        return view('orders.index', compact('orders', 'phases', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get customers (only customers)
        $customers = User::where('role', RoleEnum::CUSTOMER)
            ->where('status', 'active')
            ->get(['id', 'name', 'email']);

        // Get meetings for dropdown (only pending/completed meetings)
        $meetings = Meeting::whereIn('status', ['pending', 'completed'])
            ->with('customer')
            ->get(['id', 'scheduled_date', 'customer_id']);

        // Get sizes for order items
        $sizes = Size::orderBy('sort_order')->get(['id', 'name']);

        // Get phases (will adjust based on requires_printing in JavaScript)
        $phases = OrderPhaseEnum::forDropdown(true);

        return view('orders.create', compact('customers', 'meetings', 'sizes', 'phases'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        // Validate the main order data
        $validated = $request->validated();

        // Start database transaction
        DB::beginTransaction();

        try {
            // Calculate total price from items
            $totalPrice = 0;

            // Create the order - use validated data (customer_id, meeting_id, requires_printing, current_phase, created_by)
            $order = Order::create([
                'customer_id' => $validated['customer_id'],
                'meeting_id' => $validated['meeting_id'] ?? null,
                'requires_printing' => $validated['requires_printing'] ?? false,
                'current_phase' => $validated['current_phase'],
                'total_price' => 0, // Will update after calculating
                'created_by' => Auth::id(), // From StoreOrderRequest validated() method
            ]);

            // Process each order item
            foreach ($validated['items'] as $itemData) {
                // Create order item
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'name' => $itemData['name'],
                    'fabric_name' => $itemData['fabric_name'] ?? null,
                    'has_printing' => $itemData['has_printing'] ?? false,
                    'description' => $itemData['description'] ?? null,
                    'single_price' => $itemData['single_price'],
                ]);

                // Process sizes for this item
                foreach ($itemData['sizes'] as $sizeData) {
                    // Create order item size (with quantity)
                    OrderItemSize::create([
                        'order_item_id' => $orderItem->id,
                        'size_id' => $sizeData['size_id'],
                        'quantity' => $sizeData['quantity'],
                    ]);

                    // Add to total price: single_price * quantity
                    $totalPrice += $itemData['single_price'] * $sizeData['quantity'];
                }
            }

            // Update order with calculated total price
            $order->update(['total_price' => $totalPrice]);

            // Commit transaction
            DB::commit();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Order created successfully!');
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // Authorization - For customers, check if order belongs to them
        if (Auth::user()->role === RoleEnum::CUSTOMER && $order->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Eager load all related data
        $order->load([
            'customer',
            'meeting',
            'creator',
            'items.itemSizes.size',
            'items.sizes' // via many-to-many
        ]);

        // Calculate item totals for display
        $itemTotals = [];
        foreach ($order->items as $item) {
            $totalQuantity = 0;
            $totalPrice = 0;

            foreach ($item->itemSizes as $itemSize) {
                $totalQuantity += $itemSize->quantity;
                $totalPrice += $item->single_price * $itemSize->quantity;
            }
            $itemTotals[$item->id] = [
                'quantity' => $totalQuantity,
                'total_price' => $totalPrice,
            ];
        }

        return view('orders.show', compact('order', 'itemTotals'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {

        // Additional check: ensure order is in editable phase
        if (!in_array($order->current_phase, [
            OrderPhaseEnum::PENDING->value,
            OrderPhaseEnum::CUTTING->value
        ])) {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Order cannot be edited in the current phase.');
        }

        // Authorization is handled in UpdateOrderRequest
        $order->load(['items.itemSizes', 'items.sizes']);

        // Get customers
        $customers = User::where('role', RoleEnum::CUSTOMER)
            ->where('status', 'active')
            ->get(['id', 'name', 'email']);

        // Get meetings
        $meetings = Meeting::whereIn('status', ['pending', 'completed'])
            ->with('customer')
            ->get(['id', 'scheduled_date', 'customer_id']);

        // Get sizes
        $sizes = Size::orderBy('sort_order')->get(['id', 'name']);

        // Get phases based on requires_printing
        $phases = OrderPhaseEnum::forDropdown($order->requires_printing);

        return view('orders.edit', compact('order', 'customers', 'meetings', 'sizes', 'phases'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {

        // Get validated data
        $validated = $request->validated();

        // Update the order
        $order->update($validated);

        // If order is completed, set completed_at
        if ($order->current_phase === OrderPhaseEnum::COMPLETED->value && !$order->completed_at) {
            $order->update(['completed_at' => now()]);
        }

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Order updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {

        // Only allow deletion in pending phase
        if ($order->current_phase !== OrderPhaseEnum::PENDING->value) {
            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Only pending orders can be deleted.');
        }

        // Start transaction for safe deletion
        DB::beginTransaction();

        try {
            // Delete related records through cascade
            $order->delete();

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', 'Order deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('orders.show', $order->id)
                ->with('error', 'Failed to delete order: ' . $e->getMessage());
        }
    }

    public function myOrders(Request $request)
{
    if (Auth::user()->role !== RoleEnum::CUSTOMER) {
        return redirect()->route('orders.index');
    }

    $orders = Order::with(['customer','meeting','creator','items.itemSizes'])
        ->where('customer_id', Auth::id())
        ->latest()
        ->paginate(10);

    return view('orders.index', compact('orders'));
}

}

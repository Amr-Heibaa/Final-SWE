<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Http\Requests\AdminRequest;
use App\Models\Meeting;
use App\Models\Order;
use App\Models\User;
use App\Models\Size;
use Illuminate\Support\Facades\DB;
use App\Enums\SizeEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Enums\OrderPhaseEnum;
use App\Http\Requests\StoreOrderRequest;
use App\Models\OrderItem;
use App\Models\OrderItemSize;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $role = $user->role instanceof RoleEnum ? $user->role->value : (string)$user->role;

        // CUSTOMER: نفس الوضع القديم (meetings بتاعته)
        if ($role === RoleEnum::CUSTOMER->value || $role === 'customer') {
            $meetings = Meeting::where('customer_id', $user->id)->latest()->paginate(10);
            return view('dashboard', compact('meetings'));
        }

        // ADMIN: يشوف customers اللي عنده + orders بتاعتهم
        if ($role === RoleEnum::ADMIN->value || $role === 'admin') {
            $customerIds = Order::where('created_by', $user->id)
                ->pluck('customer_id')
                ->unique()
                ->values();

            $customers = User::where('role', RoleEnum::CUSTOMER->value)
                ->whereIn('id', $customerIds)
                ->latest()
                ->paginate(10);

            $orders = Order::with('customer')
                ->where('created_by', $user->id)
                ->latest()
                ->paginate(10);

            return view('dashboard', compact('customers', 'orders'));
        }

        // SUPERADMIN: يشوف admins + customers + orders (كله)
        if ($role === RoleEnum::SUPER_ADMIN->value || $role === 'superAdmin') {
            $admins = User::where('role', RoleEnum::ADMIN->value)->latest()->paginate(10);
            $customers = User::where('role', RoleEnum::CUSTOMER->value)->latest()->paginate(10);
            $orders = Order::with('customer')->latest()->paginate(10);

            return view('dashboard', compact('admins', 'customers', 'orders'));
        }

        abort(403);
    }

    // ==================== ADMIN MANAGEMENT (SUPER_ADMIN ONLY) ====================

    /**
     * Display a listing of all admins
     */
    public function index()
    {
        if (Auth::user()->role !== RoleEnum::SUPER_ADMIN) {
            abort(403, 'Unauthorized');
        }

        $admins = User::where('role', RoleEnum::ADMIN)->get();

        return view('admin.admin-index', ['admins' => $admins]);
    }

    /**
     * Show the form for creating a new admin
     */
    public function create()
    {
        if (Auth::user()->role !== RoleEnum::SUPER_ADMIN) {
            abort(403, 'Unauthorized');
        }

        return view('admin.admin-create');
    }

    /**
     * Store a newly created admin
     */
    public function store(AdminRequest $request)
    {
        if (Auth::user()->role !== RoleEnum::SUPER_ADMIN) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = RoleEnum::ADMIN;

        User::create($validated);

        return redirect()->route('admin.index')->with('success', 'Admin created successfully');
    }

    /**
     * Display the specified admin
     */
    public function show(User $user)
    {
        if (Auth::user()->role !== RoleEnum::SUPER_ADMIN) {
            abort(403, 'Unauthorized');
        }

        if ($user->role !== RoleEnum::ADMIN) {
            abort(404, 'Admin not found');
        }

        return view('admin.show', ['admin' => $user]);
    }

    /**
     * Show the form for editing an admin
     */
    public function edit(User $user)
    {
        if (Auth::user()->role !== RoleEnum::SUPER_ADMIN) {
            abort(403, 'Unauthorized');
        }

        if ($user->role !== RoleEnum::ADMIN) {
            abort(404, 'Admin not found');
        }

        return view('admin.edit', ['admin' => $user]);
    }

    /**
     * Update the specified admin
     */
    public function update(AdminRequest $request, User $user)
    {
        if (Auth::user()->role !== RoleEnum::SUPER_ADMIN) {
            abort(403, 'Unauthorized');
        }

        if ($user->role !== RoleEnum::ADMIN) {
            abort(404, 'Admin not found');
        }

        $validated = $request->validated();

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.show', $user)->with('success', 'Admin updated successfully');
    }

    /**
     * Delete the specified admin
     */
    public function destroy(User $user)
    {
        if (Auth::user()->role !== RoleEnum::SUPER_ADMIN) {
            abort(403, 'Unauthorized');
        }

        if ($user->role !== RoleEnum::ADMIN) {
            abort(404, 'Admin not found');
        }

        $user->forceDelete();

        return redirect()->route('admin.index')->with('success', 'Admin deleted successfully');
    }

    // ==================== CUSTOMER MANAGEMENT (SUPER_ADMIN & ADMIN) ====================

    /**
     * Display a listing of customers (Super Admin sees all, Admin sees their own)
     */
    public function customerIndex()
    {
        $user = Auth::user();

        if ($user->role !== RoleEnum::SUPER_ADMIN && $user->role !== RoleEnum::ADMIN) {
            abort(403, 'Unauthorized');
        }

        if ($user->role === RoleEnum::SUPER_ADMIN) {
            $customers = User::where('role', RoleEnum::CUSTOMER)->get();
        } else {
            // Admin sees only their customers
            $customers = User::where('role', RoleEnum::CUSTOMER)
                ->where('admin_id', $user->id)
                ->get();
        }

        return view('admin.customer-index', ['customers' => $customers]);
    }

    /**
     * Show the form for creating a new customer
     */
    public function customerCreate()
    {
        $user = Auth::user();

        if ($user->role !== RoleEnum::SUPER_ADMIN && $user->role !== RoleEnum::ADMIN) {
            abort(403, 'Unauthorized');
        }

        return view('admin.customer-create');
    }

    /**
     * Store a newly created customer
     */
    public function customerStore(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== RoleEnum::SUPER_ADMIN && $user->role !== RoleEnum::ADMIN) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:20'],
            'brand_name' => ['required', 'string', 'max:255'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = RoleEnum::CUSTOMER;

        // Assign to admin if admin is creating, super_admin creates unassigned
        if ($user->role === RoleEnum::ADMIN) {
            $validated['admin_id'] = $user->id;
        }

        User::create($validated);

        return redirect()->route('admin.customer-create')->with('success', 'Customer created successfully');
    }

    /**
     * Display the specified customer (NO PASSWORD SHOWN)
     */
    public function customerShow(User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser->role !== RoleEnum::SUPER_ADMIN && $currentUser->role !== RoleEnum::ADMIN) {
            abort(403, 'Unauthorized');
        }

        if ($user->role !== RoleEnum::CUSTOMER) {
            abort(404, 'Customer not found');
        }

        // Admin can only see their own customers
        if ($currentUser->role === RoleEnum::ADMIN && $user->admin_id !== $currentUser->id) {
            abort(403, 'Unauthorized');
        }

        return view('admin.customer-show', ['customer' => $user]);
    }

    /**
     * Show the form for editing a customer (NO PASSWORD FIELD)
     */
    public function customerEdit(User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser->role !== RoleEnum::SUPER_ADMIN && $currentUser->role !== RoleEnum::ADMIN) {
            abort(403, 'Unauthorized');
        }

        if ($user->role !== RoleEnum::CUSTOMER) {
            abort(404, 'Customer not found');
        }

        // Admin can only edit their own customers
        if ($currentUser->role === RoleEnum::ADMIN && $user->admin_id !== $currentUser->id) {
            abort(403, 'Unauthorized');
        }

        return view('admin.customer-edit', ['customer' => $user]);
    }

    /**
     * Update the specified customer (NO PASSWORD UPDATE)
     */
    public function customerUpdate(Request $request, User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser->role !== RoleEnum::SUPER_ADMIN && $currentUser->role !== RoleEnum::ADMIN) {
            abort(403, 'Unauthorized');
        }

        if ($user->role !== RoleEnum::CUSTOMER) {
            abort(404, 'Customer not found');
        }

        // Admin can only update their own customers
        if ($currentUser->role === RoleEnum::ADMIN && $user->admin_id !== $currentUser->id) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone' => ['required', 'string', 'max:20'],
            'brand_name' => ['required', 'string', 'max:255'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.customer-show', $user)->with('success', 'Customer updated successfully');
    }

    /**
     * Delete the specified customer
     */
    public function customerDestroy(User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser->role !== RoleEnum::SUPER_ADMIN && $currentUser->role !== RoleEnum::ADMIN) {
            abort(403, 'Unauthorized');
        }

        if ($user->role !== RoleEnum::CUSTOMER) {
            abort(404, 'Customer not found');
        }

        // Admin can only delete their own customers
        if ($currentUser->role === RoleEnum::ADMIN && $user->admin_id !== $currentUser->id) {
            abort(403, 'Unauthorized');
        }

        $user->forceDelete();

        return redirect()->route('admin.customer-index')->with('success', 'Customer deleted successfully');
    }

    // ==================== ORDER MANAGEMENT (ADMIN & SUPER_ADMIN) ====================

    /**
     * Display a listing of all orders
     */
    public function orderIndex(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== RoleEnum::SUPER_ADMIN && $user->role !== RoleEnum::ADMIN) {
            abort(403, 'Unauthorized');
        }

        $orders = Order::with(['customer', 'meeting', 'creator'])->latest();

        if ($request->filled('customer_id')) $orders->where('customer_id', $request->customer_id);
        if ($request->filled('meeting_id'))  $orders->where('meeting_id', $request->meeting_id);
        if ($request->filled('phase'))       $orders->where('current_phase', $request->phase);

        if ($request->has('requires_printing')) {
            $orders->where('requires_printing', $request->boolean('requires_printing'));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $orders->whereHas('customer', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $orders = $orders->paginate(20);

        $phases = OrderPhaseEnum::forDropdown(true);
        $customers = User::where('role', RoleEnum::CUSTOMER)->get(['id', 'name', 'email']);

        return view('admin.order-index', compact('orders', 'phases', 'customers'));
    }

    public function ordershow(Order $order)
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
     * Show the form for creating a new order
     */
    public function orderCreate()
    {
        $user = Auth::user();

        if ($user->role !== RoleEnum::SUPER_ADMIN && $user->role !== RoleEnum::ADMIN) {
            abort(403, 'Unauthorized');
        }

        $customers = User::where('role', RoleEnum::CUSTOMER)->get(['id', 'name', 'email', 'brand_name']);
        $meetings = Meeting::whereIn('status', ['pending', 'completed'])
            ->with('customer')
            ->get(['id', 'scheduled_date', 'customer_id', 'name']);

        // ✅ من DB مش Enum Arrays
        $sizes = Size::orderBy('sort_order')->get(['id', 'name']);

        $phases = OrderPhaseEnum::cases();

        return view('admin.create-order', compact('customers', 'meetings', 'sizes', 'phases'));
    }

    /**
     * Show the form for editing an order
     */
    public function orderEdit(Order $order)
    {
        $user = Auth::user();

        if ($user->role !== RoleEnum::SUPER_ADMIN && $user->role !== RoleEnum::ADMIN) {
            abort(403, 'Unauthorized');
        }

        if ($order->current_phase !== OrderPhaseEnum::PENDING && $order->current_phase !== OrderPhaseEnum::CUTTING) {
            return redirect()->route('admin.order-show', $order->id)
                ->with('error', 'Order cannot be edited in the current phase.');
        }

        $order->load(['items.itemSizes.size', 'customer', 'meeting']);

        $customers = User::where('role', RoleEnum::CUSTOMER)->get(['id', 'name', 'email', 'brand_name']);
        $meetings = Meeting::whereIn('status', ['pending', 'completed'])
            ->with('customer')
            ->get(['id', 'scheduled_date', 'customer_id', 'name']);
        $sizes = Size::all(['id', 'name', 'sort_order']);
        $phases = OrderPhaseEnum::cases();

        return view('admin.order-edit', compact('order', 'customers', 'meetings', 'sizes', 'phases'));
    }
    public function orderstore(StoreOrderRequest $request)
{
    $validated = $request->validated();
    $customer = User::findOrFail($validated['customer_id']);

    DB::beginTransaction();

    try {
        $totalPrice = 0;

        $order = Order::create([
            'customer_id'       => $customer->id,
            'customer_name'     => $customer->name,
            'brand_name'        => $customer->brand_name ?? null,
            'meeting_id'        => $validated['meeting_id'] ?? null,
            'requires_printing' => $validated['requires_printing'] ?? false,
            'current_phase'     => $validated['current_phase'], // لازم يطابق نوع العمود في DB
            'total_price'       => 0,
            'created_by'        => Auth::id(),
        ]);

        foreach ($validated['items'] as $itemData) {
            $orderItem = OrderItem::create([
                'order_id'      => $order->id,
                'name'          => $itemData['name'],
                'fabric_name'   => $itemData['fabric_name'] ?? null,
                'has_printing'  => $itemData['has_printing'] ?? false,
                'description'   => $itemData['description'] ?? null,
                'single_price'  => $itemData['single_price'],
            ]);

            foreach ($itemData['sizes'] as $sizeData) {
                OrderItemSize::create([
                    'order_item_id' => $orderItem->id,
                    'size_id'       => $sizeData['size_id'],   // ✅ ID رقمي من جدول sizes
                    'quantity'      => $sizeData['quantity'],
                ]);

                $totalPrice += $itemData['single_price'] * $sizeData['quantity'];
            }
        }

        $order->update(['total_price' => $totalPrice]);

        DB::commit();

        // ✅ redirect صح
        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Order created successfully!');
    } catch (\Exception $e) {
        DB::rollBack();

        return back()->withInput()
            ->with('error', 'Failed to create order: ' . $e->getMessage());
    }
}

    public function orderdestroy(Order $order)
{
    if ($order->current_phase !== OrderPhaseEnum::PENDING->value) {
        return redirect()->route('admin.orders.show', $order->id)
            ->with('error', 'Only pending orders can be deleted.');
    }

    DB::beginTransaction();

    try {
        $order->delete();
        DB::commit();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()->route('admin.orders.show', $order->id)
            ->with('error', 'Failed to delete order: ' . $e->getMessage());
    }
}

}

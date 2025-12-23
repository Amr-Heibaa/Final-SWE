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
use App\Http\Requests\UpdateAdminRequest;
use App\Models\OrderItem;
use App\Models\OrderItemSize;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    
    public function dashboard()
    {
        $user = Auth::user();
        $role = $user->role instanceof RoleEnum ? $user->role->value : (string)$user->role;

        if ($role === RoleEnum::CUSTOMER->value || $role === 'customer') {
            $meetings = Meeting::where('customer_id', $user->id)->latest()->paginate(10);
            return view('dashboard', compact('meetings'));
        }

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

return redirect()->route('admin.index')
    ->with('success', 'Admin updated successfully');    }

    /**
     * Show the form for editing an admin
     */
    public function edit(User $user)
    {
        $current = Auth::user();
        $role = $current->role instanceof RoleEnum ? $current->role->value : (string) $current->role;

        if ($role !== RoleEnum::SUPER_ADMIN->value) {
            abort(403, 'Unauthorized');
        }

        $userRole = $user->role instanceof RoleEnum ? $user->role->value : (string) $user->role;
        if ($userRole !== RoleEnum::ADMIN->value) {
            abort(404, 'Admin not found');
        }

        return view('admin.admin-edit', ['admin' => $user]);
    }

    /**
     * Update the specified admin
     */
   public function update(UpdateAdminRequest $request, User $user)
{
    if ($user->role !== RoleEnum::ADMIN) {
        abort(404, 'Admin not found');
    }

    $data = $request->validated();

    if (empty($data['password'])) {
        unset($data['password']);
    }

    $user->update($data);

    return redirect()
        ->route('admin.show', $user)
        ->with('success', 'Admin updated successfully');
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

        $user->Delete();

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

    $customers = User::where('role', RoleEnum::CUSTOMER)->get();

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
        // if ($user->role === RoleEnum::ADMIN) {
        //     $validated['admin_id'] = $user->id;
        // }

        User::create($validated);

        return redirect()->route('admin.customer-create')->with('success', 'Customer created successfully');
    }

    /**
     * Display the specified customer (NO PASSWORD SHOWN)
     */
    public function customerShow(User $user)
    {
           $Currentuser = Auth::user();
         

    
           if ($Currentuser->role !== RoleEnum::SUPER_ADMIN && $Currentuser->role !== RoleEnum::ADMIN) {
            abort(403, 'Unauthorized');
        }
      

    


        if ($user->role !== RoleEnum::CUSTOMER) {
            abort(404, 'Customer not found');
        }

        // Admin can only see their own customers
   

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
      

        $user->Delete();

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
        if (Auth::user()->role === RoleEnum::CUSTOMER && $order->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $order->load([
            'customer',
            'meeting',
            'creator',
            'items.itemSizes.size',
        ]);

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

        return view('admin.order-show', compact('order', 'itemTotals'));
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

        $phase = $order->current_phase instanceof \App\Enums\OrderPhaseEnum
            ? $order->current_phase->value
            : (string) $order->current_phase;

        if (!in_array($phase, [
            OrderPhaseEnum::PENDING->value,
            OrderPhaseEnum::CUTTING->value,
        ], true)) {
            return redirect()->route('admin.orders.show', $order->id)
                ->with('error', 'Order cannot be edited in the current phase.');
        }


        $order->load(['items.itemSizes.size', 'customer', 'meeting']);

        $customers = User::where('role', RoleEnum::CUSTOMER)->get(['id', 'name', 'email', 'brand_name']);
        $meetings = Meeting::whereIn('status', ['pending', 'completed'])
            ->with('customer')
            ->get(['id', 'scheduled_date', 'customer_id', 'name']);
        $sizes = Size::all(['id', 'name', 'sort_order']);
        $phases = OrderPhaseEnum::forDropdown((bool) $order->requires_printing);

        $orderItems = $order->items->map(function ($it) {
            return [
                'id'           => $it->id,
                'name'         => $it->name,
                'fabric_name'  => $it->fabric_name,
                'has_printing' => (bool) $it->has_printing,
                'description'  => $it->description,
                'single_price' => $it->single_price,
                'sizes'        => $it->itemSizes->map(function ($sz) {
                    return [
                        'size_id'  => $sz->size_id,
                        'quantity' => $sz->quantity,
                    ];
                })->values(),
            ];
        })->values();
        $sizesForJs = $sizes->map(function ($s) {
            return ['id' => $s->id, 'name' => $s->name];
        })->values();


        return view('admin.order-edit', compact(
            'order',
            'customers',
            'meetings',
            'sizes',
            'sizesForJs',
            'phases',
            'orderItems'
        ));
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
                'current_phase'     => $validated['current_phase'], 
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
                        'size_id'       => $sizeData['size_id'],   
                        'quantity'      => $sizeData['quantity'],
                    ]);

                    $totalPrice += $itemData['single_price'] * $sizeData['quantity'];
                }
            }

            $order->update(['total_price' => $totalPrice]);

            DB::commit();

            
            return redirect()->route('admin.orders.show', $order->id)
                ->with('success', 'Order created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }


    public function orderUpdate(Request $request, Order $order)
    {
        $user = Auth::user();

        if ($user->role !== RoleEnum::SUPER_ADMIN && $user->role !== RoleEnum::ADMIN) {
            abort(403, 'Unauthorized');
        }

        if ($request->filled('current_phase') && !$request->has('items')) {

            $allowed = array_keys(OrderPhaseEnum::forDropdown((bool) $order->requires_printing));

            $data = $request->validate([
                'current_phase' => ['required', Rule::in($allowed)],
                'redirect_to'   => ['nullable', 'string'],
            ]);

            $order->update([
                'current_phase' => $data['current_phase'],
            ]);

            return redirect($request->input('redirect_to', route('admin.orders.index')))
                ->with('success', 'Phase updated successfully!');
        }

        $phase = $order->current_phase instanceof OrderPhaseEnum
            ? $order->current_phase->value
            : (string) $order->current_phase;

        if (!in_array($phase, [OrderPhaseEnum::PENDING->value, OrderPhaseEnum::CUTTING->value], true)) {
            return redirect()->route('admin.orders.show', $order->id)
                ->with('error', 'Order cannot be edited in the current phase.');
        }

        $allowed = array_keys(OrderPhaseEnum::forDropdown((bool) $order->requires_printing));

        $validated = $request->validate([
            'meeting_id'        => ['nullable', 'exists:meetings,id'],
            'requires_printing' => ['nullable', 'boolean'],
            'current_phase'     => ['required', Rule::in($allowed)],

            'items'                         => ['required', 'array', 'min:1'],
            'items.*.id'                    => ['nullable', 'exists:order_items,id'],
            'items.*.name'                  => ['required', 'string', 'max:255'],
            'items.*.fabric_name'           => ['nullable', 'string', 'max:255'],
            'items.*.has_printing'          => ['nullable', 'boolean'],
            'items.*.description'           => ['nullable', 'string'],
            'items.*.single_price'          => ['required', 'numeric', 'min:0'],

            'items.*.sizes'                 => ['required', 'array', 'min:1'],
            'items.*.sizes.*.size_id'       => ['required', 'exists:sizes,id'],
            'items.*.sizes.*.quantity'      => ['required', 'integer', 'min:1'],
        ]);

        DB::beginTransaction();

        try {
            $order->update([
                'meeting_id'        => $validated['meeting_id'] ?? null,
                'requires_printing' => (bool)($validated['requires_printing'] ?? false),
                'current_phase'     => $validated['current_phase'],
            ]);

            $keptItemIds = [];
            $total = 0;

            foreach ($validated['items'] as $itemData) {

                if (!empty($itemData['id'])) {
                    $item = OrderItem::where('order_id', $order->id)
                        ->where('id', $itemData['id'])
                        ->firstOrFail();

                    $item->update([
                        'name'         => $itemData['name'],
                        'fabric_name'  => $itemData['fabric_name'] ?? null,
                        'has_printing' => (bool)($itemData['has_printing'] ?? false),
                        'description'  => $itemData['description'] ?? null,
                        'single_price' => $itemData['single_price'],
                    ]);
                } else {
                    $item = OrderItem::create([
                        'order_id'     => $order->id,
                        'name'         => $itemData['name'],
                        'fabric_name'  => $itemData['fabric_name'] ?? null,
                        'has_printing' => (bool)($itemData['has_printing'] ?? false),
                        'description'  => $itemData['description'] ?? null,
                        'single_price' => $itemData['single_price'],
                    ]);
                }

                $keptItemIds[] = $item->id;

                $item->itemSizes()->delete();

                foreach ($itemData['sizes'] as $sizeData) {
                    OrderItemSize::create([
                        'order_item_id' => $item->id,
                        'size_id'       => $sizeData['size_id'],
                        'quantity'      => $sizeData['quantity'],
                    ]);

                    $total += $itemData['single_price'] * $sizeData['quantity'];
                }
            }

            $order->items()->whereNotIn('id', $keptItemIds)->delete();

            $order->update(['total_price' => $total]);

            DB::commit();

            return redirect()->route('admin.orders.show', $order->id)
                ->with('success', 'Order updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update order: ' . $e->getMessage());
        }
    }

    public function orderdestroy(Order $order)
{
    $phase = $order->current_phase instanceof OrderPhaseEnum
        ? $order->current_phase->value
        : (string) $order->current_phase;

    if ($phase !== OrderPhaseEnum::PENDING->value) {
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

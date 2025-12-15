<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Http\Requests\AdminRequest;
use App\Models\Meeting;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MeetingController;

class AdminController extends Controller
{
    // ==================== ADMIN MANAGEMENT (SUPER_ADMIN ONLY) ====================

    /**
     * Display a listing of all admins
     */
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

    public function index()
    {
        if (Auth::user()->role !== RoleEnum::SUPER_ADMIN) {
            abort(403, 'Unauthorized');
        }

        $admins = User::where('role', RoleEnum::ADMIN)->get();

        return view('admin.index', ['admins' => $admins]);
    }

    /**
     * Show the form for creating a new admin
     */
    public function create()
    {
        if (Auth::user()->role !== RoleEnum::SUPER_ADMIN) {
            abort(403, 'Unauthorized');
        }

        return view('admin.create');
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

        if (!in_array($user->role, [RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])) {
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

        if (!in_array($user->role, [RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])) {
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

        if (!in_array($user->role, [RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])) {
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

        return redirect()->route('admin.customer-index')->with('success', 'Customer created successfully');
    }

    /**
     * Display the specified customer (NO PASSWORD SHOWN)
     */
    public function customerShow(User $user)
    {
        $currentUser = Auth::user();

        if (!in_array($currentUser->role, [RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])) {
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

        if (!in_array($currentUser->role, [RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])) {
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

        if (!in_array($currentUser->role, [RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])) {
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

        if (!in_array($currentUser->role, [RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])) {
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
}

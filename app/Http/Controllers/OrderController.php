<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        // require auth for all actions
        $this->middleware('auth');

        // optional: if you still use route names like orders.my-orders etc.
        $this->middleware(function ($request, $next) {
            $routeName = optional($request->route())->getName();

            // pages allowed for customers in this controller
            $allowedForCustomer = ['orders.index', 'orders.show', 'orders.my-orders'];

            if (Auth::check() && Auth::user()->role === RoleEnum::CUSTOMER) {
                if (!\in_array($routeName, $allowedForCustomer, true)) {
                    return redirect()->route('meetings.index')
                        ->with('error', 'You do not have permission to access order management.');
                }
            }

            return $next($request);
        });
    }

    /**
     * List the logged-in customer's orders.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // only orders belonging to this customer
        $orders = Order::with(['customer', 'meeting', 'creator', 'items.itemSizes'])
            ->where('customer_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show a specific order if it belongs to the logged-in customer.
     */
    public function show(Order $order)
    {
        $user = Auth::user();

        // block customers from seeing others' orders
        if ($user->role === RoleEnum::CUSTOMER && $order->customer_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // eager load related data for display
        $order->load([
            'customer',
            'meeting',
            'creator',
            'items.itemSizes.size',
            'items.sizes',
        ]);

        // calculate totals per item for display
        $itemTotals = [];
        foreach ($order->items as $item) {
            $totalQuantity = 0;
            $totalPrice    = 0;

            foreach ($item->itemSizes as $itemSize) {
                $totalQuantity += $itemSize->quantity;
                $totalPrice    += $item->single_price * $itemSize->quantity;
            }

            $itemTotals[$item->id] = [
                'quantity'    => $totalQuantity,
                'total_price' => $totalPrice,
            ];
        }

        return view('orders.show', compact('order', 'itemTotals'));
    }

    /**
     * Optional: explicit route for "My Orders" if you still use it.
     */
    public function myOrders(Request $request)
    {
        if (Auth::user()->role !== RoleEnum::CUSTOMER) {
            return redirect()->route('orders.index');
        }

        $orders = Order::with(['customer', 'meeting', 'creator', 'items.itemSizes'])
            ->where('customer_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }
}

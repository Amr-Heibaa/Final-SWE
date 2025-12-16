<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function __construct()
    {
        // Restrict to admins / super admins as you prefer (adjust middleware to your app)
        $this->middleware(['auth', 'role:super_admin,admin']);
    }

    /**
     * List all available sizes.
     * GET /admin/sizes
     */
    public function index()
    {
        $sizes = Size::orderBy('sort_order')->get();

        return view('sizes.index', compact('sizes'));
    }

    /**
     * Show a specific size.
     * GET /admin/sizes/{size}
     */
    public function show(Size $size)
    {
        return view('sizes.show', compact('size'));
    }

    /**
     * Store a new size.
     * POST /admin/sizes
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255', 'unique:sizes,name'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        Size::create($data);

        return redirect()
            ->back()
            ->with('success', 'Size created successfully.');
    }

    /**
     * Update an existing size.
     * PUT/PATCH /admin/sizes/{size}
     */
    public function update(Request $request, Size $size)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255', 'unique:sizes,name,' . $size->id],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        $size->update($data);

        return redirect()
            ->back()
            ->with('success', 'Size updated successfully.');
    }

    /**
     * Delete a size.
     * DELETE /admin/sizes/{size}
     */
    public function destroy(Size $size)
    {
        // Optional: prevent deleting sizes that are already used in order_item_sizes
        if ($size->itemSizes()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete size that is used in order items.');
        }

        $size->delete();

        return redirect()
            ->back()
            ->with('success', 'Size deleted successfully.');
    }
}

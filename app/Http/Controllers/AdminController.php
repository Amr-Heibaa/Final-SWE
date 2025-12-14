<?php

namespace App\Http\Controllers;
use App\Http\Requests\AdminRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Hash;
class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if(Auth::user()->role !=='super_admin')
        {
            abort(403,'unauthorized');
        }
        $admins=User::whereIn('role',['admin'])->get();
        return view('admin.index',['admins'=>$admins]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        if (Auth::user()->role !='super_admin'){
            abort(403,'unauthorized');
        }
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminRequest $request)
    {
        //
        if (Auth::user()->role !='super_admin'){
            abort(403,'unauthorized');
        }
                $validated = $request->validated();
                $validated['password']=hash::make($validated['password']);
                User::create($validated);

                return redirect()->route('admin.index')->with('succes','admin created succesfully');
                
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
        if (Auth::user()->role !='super_admin'){
            abort(403,'unauthorized');
        }
        if(!in_array($user->role,['admin']))
        {
            abort(404,'admin not found');
        }
        return view('admin.show',['admin'=>$user]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
          if (Auth::user()->role !='super_admin'){
            abort(403,'unauthorized');
        }
        if(!in_array($user->role,['admin']))
        {
            abort(404,'admin not found');
        }
        return view('admin.edit',['admin'=>$user]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
          if (!in_array($user->role, ['admin', 'admin_manager'])) {
            abort(404, 'Admin not found');
        }

        // Get validated data from AdminRequest (no duplicate validation!)
        $validated = $request->validated();

        // ★ UPDATE DATA IN DATABASE ★
        $user->update($validated);

        return redirect()->route('admin.show', $user)->with('success', 'Admin updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
                if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Unauthorized');
        }

        if (!in_array($user->role, ['admin', 'admin_manager'])) {
            abort(404, 'Admin not found');
        }

        // ★ FORCE DELETE (PERMANENT) ★
        $user->forceDelete();

        return redirect()->route('admin.index')->with('success', 'Admin permanently deleted');
    }

    
}

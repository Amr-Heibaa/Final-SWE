<?php

namespace App\Http\Controllers;

use App\Enums\MeetingStatus;
use App\Enums\RoleEnum;
use App\Http\Requests\MeetingRequest;
use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meetings = Meeting::query()
            ->where('customer_id', auth()->id())
            ->latest('scheduled_date')
            ->paginate(10);

        // Lma n3mll el views 
        return view('meeting.index', [
            'meetings' => $meetings,
            'pageTitle' => 'My Meetings',
        ]);

        // return response()->json($meetings);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Lma n3mll el views 
        return view('meeting.form', [
            'pageTitle' => 'Create New Meeting',
        ]);

        // return response()->json([
        //     'message' => 'Provide scheduled_date to create a meeting.',
        // ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MeetingRequest $meetingRequest)
    {
        $data = $meetingRequest->validated();

        $meeting = Meeting::create([
            'customer_id'    => auth()->id(),
            'name'           => $data['name'],
            'phone'          => $data['phone'],
            'brand_name'     => $data['brand_name'],
            'scheduled_date' => $data['scheduled_date'],
            'status'         => 'pending',
        ]);
        // Lma n3mll el views 
        return redirect('/dashboard')->with('success', 'Meeting created successfully');


    }

    /**
     * Display the specified resource.
     */
    public function show(Meeting $meeting)
    {

        abort_unless($meeting->customer_id === auth()->id(), 403);
        return view('Meeting.show', [
            'meeting' => $meeting,
            'pageTitle' => 'Meeting Details',
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meeting $meeting)
    {
        abort_unless($meeting->customer_id === auth()->id(), 403);

    return view('Meeting.edit', [
        'meeting' => $meeting,
        'pageTitle' => 'Edit Meeting',
    ]);
        // return response()->json($meeting);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MeetingRequest $meetingRequest, Meeting $meeting)
    {
        abort_unless($meeting->customer_id === auth()->id(), 403);

        $data = $meetingRequest->validated();

        $meeting->update([
            'name'           => $data['name'] ?? $meeting->name,
            'phone'          => $data['phone'] ?? $meeting->phone,
            'brand_name'     => $data['brand_name'] ?? $meeting->brand_name,
            'scheduled_date' => $data['scheduled_date'] ?? $meeting->scheduled_date,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meeting $meeting)
    {
        abort_unless($meeting->customer_id === auth()->id(), 403);

        $meeting->delete();
        // Lma n3mll el views 
        // return redirect('/meetings')->with('success', 'Meeting deleted successfully');
        return response()->json([
            'message' => 'Meeting deleted successfully',
        ]);
    }

    public function adminIndex(Request $request)
{
    $user = Auth::user();

    // أمان
    if (!in_array($user->role, [RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN])) {
        abort(403);
    }

    $query = Meeting::with('customer')->latest();

    // لو جاي customer_id من الزرار
    if ($request->filled('customer_id')) {
        $query->where('customer_id', $request->customer_id);
    }

    $meetings = $query->paginate(10);

    return view('admin.meetings-index', compact('meetings'));
}


public function updateStatus(Request $request, Meeting $meeting)
{
    $user = Auth::user();

    if (!in_array($user->role, [RoleEnum::ADMIN, RoleEnum::SUPER_ADMIN], true)) {
        abort(403);
    }

    if ($user->role === RoleEnum::ADMIN) {
        if (!$meeting->customer || $meeting->customer->admin_id !== $user->id) {
            abort(403);
        }
    }

    $data = $request->validate([
        'status' => ['required', 'string'], 
    ]);

    $meeting->status = MeetingStatus::from($data['status']);
    $meeting->save();

    return back()->with('success', 'Meeting status updated.');
}

}

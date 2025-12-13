<?php

namespace App\Http\Controllers;

use App\Http\Requests\MeetingRequest;
use App\Models\Meeting;
use Illuminate\Http\Request;

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
        // return view('meeting.index', [
        //     'meetings' => $meetings,
        //     'pageTitle' => 'My Meetings',
        // ]);

        return response()->json($meetings);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Lma n3mll el views 
        // return view('meeting.create', [
        //     'pageTitle' => 'Create New Meeting',
        // ]);

        return response()->json([
            'message' => 'Provide scheduled_date to create a meeting.',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MeetingRequest $meetingRequest)
    {
        $meeting = new Meeting();
        $meeting->customer_id = auth()->id();
        $meeting->scheduled_date = $meetingRequest['scheduled_date'];
        $meeting->status = $meetingRequest['status'] ?? 'pending';
        $meeting->save();

        // Lma n3mll el views 
        // return redirect('/meetings')->with('success', 'Meeting created successfully');

        return response()->json([
            'message' => 'Meeting created successfully',
            'meeting' => $meeting,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Meeting $meeting)
    {

        abort_unless($meeting->customer_id === auth()->id(), 403);

        // Lma n3mll el views 
        // return view('meeting.show', [
        //     'meeting' => $meeting,
        //     'pageTitle' => 'Meeting Details',
        // ]);

        return response()->json($meeting);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meeting $meeting)
    {
        abort_unless($meeting->customer_id === auth()->id(), 403);
        // Lma n3mll el views 
        // return view('meeting.edit', [
        //     'meeting' => $meeting,
        //     'pageTitle' => 'Edit Meeting',
        // ]);

        return response()->json($meeting);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MeetingRequest $meetingRequest, Meeting $meeting)
    {
   abort_unless($meeting->customer_id === auth()->id(), 403);

    $meeting->update($meetingRequest->validated());
        // Lma n3mll el views 
        // return redirect('/meetings')->with('success', 'Meeting updated successfully');
        return response()->json([
            'message' => 'Meeting updated successfully',
            'meeting' => $meeting,
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
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        return view('calendar'); // Ensure you have a calendar.blade.php view
    }
    public function loadEvents(Request $request)
    {
        $start = $request->start;
        $end = $request->end;

        $events = Event::whereBetween('start_date', [$start, $end])
            ->orWhereBetween('end_date', [$start, $end])
            ->get();

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
        ]);

        Event::create($validatedData);

        return redirect()->route('events.load')->with('success', 'Event Created Successfully');
    }
    public function show($id)
    {

        $event = Event::find($id);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        return response()->json($event);
    }
    public function update(Request $request, $id)
    {
        // Validate incoming request
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        // Update existing event
        $event = Event::findOrFail($id);
        $event->update($validatedData);

        return response()->json($event, 200);
    }
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(['success' => 'Event deleted successfully'], 200);
    }

}

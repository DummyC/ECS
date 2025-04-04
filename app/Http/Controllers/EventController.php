<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::all();
        return response()->json($events);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $start = $request->start;
        $end = $request->end;

        // Convert UTC+8 to UTC
        $startUtc = $this->convertToUtc($start);
        $endUtc = $this->convertToUtc($end);

        $event = Event::create([
            'title' => $request->title,
            'start' => $startUtc,
            'end' => $endUtc,
            'description' => $request->description,
            'allDay' => $request->boolean('allDay'),
        ]);

        return response()->json([
            'event' => $event
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $start = $request->start;
        $end = $request->end;

        // Convert UTC+8 to UTC
        $startUtc = $this->convertToUtc($start);
        $endUtc = $this->convertToUtc($end);

        $event = Event::find($id);
        $event->update([
            'title' => $request->title,
            'start' => $startUtc,
            'end' => $endUtc,
            'description' => $request->description,
            'allDay' => $request->boolean('allDay'),
        ]);

        return response()->json([
            'event' => $event
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Event::find($id)->delete();
        return response()->json(['success' => 'Event deleted successfully']);
    }

    /**
     * Convert UTC+8 to UTC.
     *
     * @param  string  $dateString
     * @return string
     */
    private function convertToUtc($dateString)
    {
        if (!$dateString) return null;
        $date = new \DateTime($dateString, new \DateTimeZone('Asia/Singapore')); // UTC+8
        $date->setTimezone(new \DateTimeZone('UTC'));
        return $date->format('Y-m-d H:i:s');
    }
}

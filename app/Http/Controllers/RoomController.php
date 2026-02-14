<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();
        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:10|unique:rooms',
            'type' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:free,occupied,cleaning',
            'description' => 'nullable|string',
        ]);
        Room::create($validated);
        return redirect('/rooms')->with('success', 'Zimmer erfolgreich erstellt');
    }

    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:10|unique:rooms,room_number,' . $room->id,
            'type' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:free,occupied,cleaning',
            'description' => 'nullable|string',
        ]);
        $room->update($validated);
        return redirect('/rooms')->with('success', 'Zimmer erfolgreich aktualisiert');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect('/rooms')->with('success', 'Zimmer erfolgreich gelöscht');
    }
}

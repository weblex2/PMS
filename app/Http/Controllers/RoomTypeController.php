<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::all();
        return view('room-types.index', compact('roomTypes'));
    }

    public function create()
    {
        return view('room-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'default_bed_count' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
        ]);

        RoomType::create($validated);
        return redirect()->route('room-types.index')->with('success', 'Zimmerart erfolgreich angelegt.');
    }

    public function edit(RoomType $roomType)
    {
        return view('room-types.edit', compact('roomType'));
    }

    public function update(Request $request, RoomType $roomType)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'default_bed_count' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
        ]);

        $roomType->update($validated);
        return redirect()->route('room-types.index')->with('success', 'Zimmerart erfolgreich aktualisiert.');
    }

    public function destroy(RoomType $roomType)
    {
        $roomType->delete();
        return redirect()->route('room-types.index')->with('success', 'Zimmerart erfolgreich gelöscht.');
    }
}

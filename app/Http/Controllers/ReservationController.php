<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['guest', 'room'])->get();
        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $guests = Guest::all();
        $rooms = Room::where('status', 'free')->get();
        return view('reservations.create', compact('guests', 'rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'integer|min:1',
            'children' => 'integer|min:0',
        ]);

        $validated['reservation_number'] = 'RES-' . strtoupper(uniqid());
        $validated['status'] = 'confirmed';
        
        // Calculate total price
        $room = Room::find($validated['room_id']);
        $days = \Carbon\Carbon::parse($validated['check_in'])->diffInDays($validated['check_out']);
        $validated['total_price'] = $room->price * $days;

        Reservation::create($validated);
        return redirect()->route('reservations.index')->with('success', 'Reservierung erfolgreich erstellt.');
    }

    public function edit(Reservation $reservation)
    {
        $guests = Guest::all();
        $rooms = Room::all();
        return view('reservations.edit', compact('reservation', 'guests', 'rooms'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
            'adults' => 'integer|min:1',
            'children' => 'integer|min:0',
            'payment_status' => 'required|in:pending,paid,refunded',
        ]);

        // Recalculate price if dates changed
        $room = Room::find($validated['room_id']);
        $days = \Carbon\Carbon::parse($validated['check_in'])->diffInDays($validated['check_out']);
        $validated['total_price'] = $room->price * $days;

        $reservation->update($validated);
        return redirect()->route('reservations.index')->with('success', 'Reservierung erfolgreich aktualisiert.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Reservierung erfolgreich gelöscht.');
    }
}

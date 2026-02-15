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
            'room_ids' => 'required|array|min:1',
            'room_ids.*' => 'exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'integer|min:1',
            'children' => 'integer|min:0',
            'payment_method' => 'required|in:cash,card,transfer,online',
            'payment_status' => 'required|in:pending,paid,advance',
            'advance_payment' => 'numeric|min:0',
            'reservation_type' => 'required|in:standard,group,business,event',
            'notes' => 'nullable|string',
        ]);

        $days = \Carbon\Carbon::parse($validated['check_in'])->diffInDays($validated['check_out']);
        $created = 0;

        foreach ($validated['room_ids'] as $roomId) {
            $room = Room::find($roomId);
            $totalPrice = $room->price * $days;
            
            Reservation::create([
                'reservation_number' => 'RES-' . strtoupper(uniqid()),
                'guest_id' => $validated['guest_id'],
                'room_id' => $roomId,
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'status' => 'confirmed',
                'total_price' => $totalPrice,
                'adults' => $validated['adults'],
                'children' => $validated['children'] ?? 0,
                'payment_status' => $validated['payment_status'],
                'payment_method' => $validated['payment_method'],
                'advance_payment' => $validated['advance_payment'] ?? 0,
                'reservation_type' => $validated['reservation_type'],
                'notes' => $validated['notes'] ?? null,
            ]);
            $created++;
        }

        return redirect()->route('reservations.index')
            ->with('success', $created . ' Reservierung(en) erfolgreich erstellt.');
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

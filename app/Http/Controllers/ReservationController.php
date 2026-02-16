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
        $reservations = Reservation::with(['guest', 'rooms'])->get();
        return view('reservations.index', compact('reservations'));
    }

    public function create(Request $request)
    {
        $checkIn = $request->input('check_in', date('Y-m-d'));
        $checkOut = $request->input('check_out', date('Y-m-d', strtotime('+3 days')));
        
        $guests = Guest::all();
        $allRooms = Room::all();
        
        $bookedRoomIds = Reservation::where(function ($query) use ($checkIn, $checkOut) {
            $query->where(function ($q) use ($checkIn, $checkOut) {
                $q->where('check_in', '<', $checkOut)->where('check_out', '>', $checkIn);
            });
        })->where('status', '!=', 'cancelled')->pluck('room_id')->toArray();
        
        $allRooms = $allRooms->map(function ($room) use ($bookedRoomIds) {
            $room->is_booked = in_array($room->id, $bookedRoomIds);
            $room->is_available = !$room->is_booked;
            return $room;
        });
        
        $guestsJson = json_encode($guests->map(function($g) {
            return ['id' => $g->id, 'name' => $g->name, 'email' => $g->email];
        }));
        
        return view('reservations.create', compact('guests', 'allRooms', 'guestsJson', 'checkIn', 'checkOut'));
    }

    public function store(Request $request)
    {
        $validRoomIds = Room::pluck('id')->toArray();
        $inputIds = $request->input('room_ids', []);
        
        $roomIds = array_filter($inputIds, function($id) use ($validRoomIds) {
            $id = (int)$id;
            return in_array($id, $validRoomIds) && $id >= 1 && $id <= 31;
        });
        $roomIds = array_values(array_unique($roomIds));
        
        if (empty($roomIds)) {
            return back()->withError('Bitte gueltige Zimmer auswaehlen.');
        }
        
        $errors = [];
        if (empty($request->input('guest_id'))) $errors[] = 'Gast ist erforderlich.';
        if (empty($request->input('check_in'))) $errors[] = 'Check-in ist erforderlich.';
        if (empty($request->input('check_out'))) $errors[] = 'Check-out ist erforderlich.';
        
        if (!empty($errors)) {
            return back()->withErrors($errors);
        }
        
        $validated = [
            'guest_id' => (int)$request->input('guest_id'),
            'check_in' => $request->input('check_in'),
            'check_out' => $request->input('check_out'),
            'status' => 'confirmed',
            'adults' => (int)$request->input('adults', 1),
            'children' => (int)$request->input('children', 0),
            'payment_status' => $request->input('payment_status', 'pending'),
            'payment_method' => $request->input('payment_method', 'cash'),
            'reservation_type' => $request->input('reservation_type', 'standard'),
            'notes' => $request->input('notes'),
            'match1' => $request->input('match1'),
            'match2' => $request->input('match2'),
        ];

        $days = \Carbon\Carbon::parse($validated['check_in'])->diffInDays($validated['check_out']);
        
        $totalPrice = 0;
        $roomPrices = [];
        foreach ($roomIds as $roomId) {
            $room = Room::find($roomId);
            $price = $room->price * $days;
            $roomPrices[$roomId] = ['price' => $price];
            $totalPrice += $price;
        }
        
        $validated['total_price'] = $totalPrice;
        
        $reservation = Reservation::create([
            'reservation_number' => 'RES-' . strtoupper(bin2hex(random_bytes(6))),
            'guest_id' => $validated['guest_id'],
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'status' => $validated['status'],
            'total_price' => $validated['total_price'],
            'adults' => $validated['adults'],
            'children' => $validated['children'],
            'payment_status' => $validated['payment_status'],
            'payment_method' => $validated['payment_method'],
            'reservation_type' => $validated['reservation_type'],
            'notes' => $validated['notes'],
        ]);
        
        $reservation->rooms()->attach($roomPrices);

        return redirect()->route('reservations.index')
            ->with('success', 'Reservierung erfolgreich erstellt.');
    }

    public function edit($id)
    {
        $reservation = Reservation::with(['guest', 'rooms'])->findOrFail($id);
        $guests = Guest::all();
        $rooms = Room::all();
        return view('reservations.edit', compact('reservation', 'guests', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        
        $validRoomIds = Room::pluck('id')->toArray();
        $inputIds = $request->input('room_ids', []);
        
        $roomIds = array_filter($inputIds, function($id) use ($validRoomIds) {
            $id = (int)$id;
            return in_array($id, $validRoomIds) && $id >= 1 && $id <= 31;
        });
        $roomIds = array_values(array_unique($roomIds));
        
        if (empty($roomIds)) {
            return back()->withError('Bitte gueltige Zimmer auswaehlen.');
        }
        
        $errors = [];
        if (empty($request->input('guest_id'))) $errors[] = 'Gast ist erforderlich.';
        if (empty($request->input('check_in'))) $errors[] = 'Check-in ist erforderlich.';
        if (empty($request->input('check_out'))) $errors[] = 'Check-out ist erforderlich.';
        
        if (!empty($errors)) {
            return back()->withErrors($errors);
        }
        
        $validated = [
            'guest_id' => (int)$request->input('guest_id'),
            'check_in' => $request->input('check_in'),
            'check_out' => $request->input('check_out'),
            'status' => $request->input('status', 'pending'),
            'adults' => (int)$request->input('adults', 1),
            'children' => (int)$request->input('children', 0),
            'payment_status' => $request->input('payment_status', 'pending'),
            'notes' => $request->input('notes'),
            'match1' => $request->input('match1'),
            'match2' => $request->input('match2'),
        ];

        $days = \Carbon\Carbon::parse($validated['check_in'])->diffInDays($validated['check_out']);
        
        $totalPrice = 0;
        $roomPrices = [];
        foreach ($roomIds as $roomId) {
            $room = Room::find($roomId);
            if (!$room) continue;
            $price = $room->price * $days;
            $roomPrices[$roomId] = ['price' => $price];
            $totalPrice += $price;
        }
        
        $validated['total_price'] = $totalPrice;
        
        $reservation->update($validated);
        $reservation->rooms()->sync($roomPrices);

        return redirect()->route('reservations.index')->with('success', 'Reservierung erfolgreich aktualisiert.');
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->rooms()->detach();
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Reservierung erfolgreich geloescht.');
    }
}

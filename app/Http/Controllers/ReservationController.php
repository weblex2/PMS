<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ReservationPath;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['guest', 'paths.rooms'])->get();
        return view('reservations.index', compact('reservations'));
    }

    public function create(Request $request)
    {
        $checkIn = $request->input('check_in', date('Y-m-d'));
        $checkOut = $request->input('check_out', date('Y-m-d', strtotime('+3 days')));
        
        $guests = Guest::all();
        $allRooms = Room::all();
        
        return view('reservations.create', compact('guests', 'allRooms', 'checkIn', 'checkOut'));
    }

    public function store(Request $request)
    {
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
            'reservation_type' => $request->input('reservation_type', 'standard'),
            'match1' => $request->input('match1'),
            'match2' => $request->input('match2'),
        ];

        $days = \Carbon\Carbon::parse($validated['check_in'])->diffInDays($validated['check_out']);
        
        $reservation = Reservation::create([
            'reservation_number' => 'RES-' . strtoupper(bin2hex(random_bytes(6))),
            'guest_id' => $validated['guest_id'],
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'status' => $validated['status'],
            'total_price' => 0,
            'adults' => $validated['adults'],
            'children' => $validated['children'],
            'payment_status' => $validated['payment_status'],
            'reservation_type' => $validated['reservation_type'],
            'match1' => $validated['match1'],
            'match2' => $validated['match2'],
        ]);
        
        $path = ReservationPath::create([
            'reservation_id' => $reservation->id,
            'path_number' => 1,
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
        ]);
        
        $roomIds = $request->input('room_ids', []);
        if (!empty($roomIds)) {
            $roomPrices = [];
            foreach ($roomIds as $roomId) {
                $room = Room::find((int)$roomId);
                if ($room) {
                    $roomPrices[(int)$roomId] = ['price' => $room->price * $days];
                }
            }
            if (!empty($roomPrices)) {
                $path->rooms()->attach($roomPrices);
            }
        }
        
        $reservation->update(['total_price' => $path->rooms->sum(function($room) {
            return $room->pivot->price;
        })]);

        return redirect()->route('reservations.index')
            ->with('success', 'Reservierung erfolgreich erstellt.');
    }

    public function edit($id)
    {
        $reservation = Reservation::with(['guest', 'paths.rooms'])->findOrFail($id);
        $guests = Guest::all();
        $rooms = Room::all();
        
        if ($reservation->paths->isEmpty()) {
            ReservationPath::create([
                'reservation_id' => $reservation->id,
                'path_number' => 1,
                'check_in' => $reservation->check_in,
                'check_out' => $reservation->check_out,
            ]);
            $reservation->load('paths.rooms');
        }
        
        $checkIn = $reservation->check_in;
        $checkOut = $reservation->check_out;
        
        $currentPathRoomIds = $reservation->paths->flatMap(function ($p) {
            return $p->rooms->pluck('id');
        })->toArray();
        
        $bookedRoomIds = ReservationPath::whereHas('reservation', function ($query) use ($checkIn, $checkOut, $id) {
            $query->where('check_in', '<', $checkOut)
                  ->where('check_out', '>', $checkIn)
                  ->where('id', '!=', $id);
        })->where('reservation_id', '!=', $id)
          ->with('rooms')
          ->get()
          ->flatMap(function ($path) {
              return $path->rooms->pluck('id');
          })
          ->unique()
          ->toArray();
        
        $rooms = $rooms->map(function ($room) use ($bookedRoomIds, $currentPathRoomIds) {
            $room->is_in_reservation = in_array($room->id, $currentPathRoomIds);
            $room->is_booked = in_array($room->id, $bookedRoomIds) && !in_array($room->id, $currentPathRoomIds);
            $room->is_available = !$room->is_booked && !$room->is_in_reservation;
            return $room;
        });
        
        return view('reservations.edit', compact('reservation', 'guests', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::with('paths')->findOrFail($id);
        
        if ($request->input('add_path')) {
            $pathCount = $reservation->paths()->count();
            ReservationPath::create([
                'reservation_id' => $reservation->id,
                'path_number' => $pathCount + 1,
                'check_in' => $request->input('check_in'),
                'check_out' => $request->input('check_out'),
            ]);
            return back()->with('success', 'Pfad hinzugefuegt.');
        }
        
        foreach ($reservation->paths as $path) {
            if ($request->input('delete_path_' . $path->id)) {
                $path->delete();
                return back()->with('success', 'Pfad geloescht.');
            }
        }
        
        $errors = [];
        if (empty($request->input('guest_id'))) $errors[] = 'Gast ist erforderlich.';
        if (empty($request->input('check_in'))) $errors[] = 'Check-in ist erforderlich.';
        if (empty($request->input('check_out'))) $errors[] = 'Check-out ist erforderlich.';
        
        if (!empty($errors)) {
            return back()->withErrors($errors);
        }
        
        $reservation->update([
            'guest_id' => (int)$request->input('guest_id'),
            'check_in' => $request->input('check_in'),
            'check_out' => $request->input('check_out'),
            'status' => $request->input('status', 'pending'),
            'adults' => (int)$request->input('adults', 1),
            'children' => (int)$request->input('children', 0),
            'payment_status' => $request->input('payment_status', 'pending'),
            'match1' => $request->input('match1'),
            'match2' => $request->input('match2'),
        ]);
        
        $totalPrice = 0;
        
        foreach ($reservation->paths as $path) {
            $pathCheckIn = $request->input('paths.' . $path->id . '.check_in');
            $pathCheckOut = $request->input('paths.' . $path->id . '.check_out');
            
            if ($pathCheckIn && $pathCheckOut) {
                $pathDays = \Carbon\Carbon::parse($pathCheckIn)->diffInDays($pathCheckOut);
                $path->update([
                    'check_in' => $pathCheckIn,
                    'check_out' => $pathCheckOut,
                ]);
            } else {
                $pathDays = \Carbon\Carbon::parse($path->check_in)->diffInDays($path->check_out);
            }
            
            $roomIds = $request->input('paths.' . $path->id . '.room_ids', []);
            $roomPrices = [];
            
            foreach ($roomIds as $roomId) {
                $room = Room::find((int)$roomId);
                if ($room) {
                    $roomPrices[(int)$roomId] = ['price' => $room->price * $pathDays];
                }
            }
            
            $path->rooms()->sync($roomPrices);
            
            $pathTotal = $path->rooms->sum(function($room) {
                return $room->pivot->price;
            });
            $totalPrice += $pathTotal;
        }
        
        $reservation->update(['total_price' => $totalPrice]);

        return redirect()->route('reservations.index')
            ->with('success', 'Reservierung erfolgreich aktualisiert.');
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        
        return redirect()->route('reservations.index')
            ->with('success', 'Reservierung geloescht.');
    }
}
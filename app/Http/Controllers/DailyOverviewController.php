<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class DailyOverviewController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->has('date')
            ? Carbon::parse($request->date)->startOfDay()
            : Carbon::today()->startOfDay();

        $endDate = (clone $startDate)->addDays(6)->endOfDay();

        $rooms = Room::orderBy('room_number')->get();

        // Load reservations with paths and their rooms
        $reservations = Reservation::where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->where('check_in', '>=', $startDate)
                       ->where('check_in', '<=', $endDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    $q->where('check_out', '>=', $startDate)
                       ->where('check_out', '<=', $endDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    $q->where('check_in', '<', $startDate)
                       ->where('check_out', '>', $startDate);
                });
            })
            ->with(['guest', 'paths.rooms'])
            ->get();

        $dateRange = [];
        for ($i = 0; $i < 7; $i++) {
            $date = (clone $startDate)->addDays($i);
            $dateRange[] = $date;
        }

        $prevDate = (clone $startDate)->subDays(1)->format('Y-m-d');
        $nextDate = (clone $startDate)->addDays(1)->format('Y-m-d');

        return view('daily-overview', compact('rooms', 'reservations', 'dateRange', 'startDate', 'prevDate', 'nextDate'));
    }
}

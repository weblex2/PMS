<?php
namespace App\Http\Controllers;
use App\Models\Reservation;
use App\Models\Guest;
use App\Models\Journal;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $stats = [
            arrivals_today => Reservation::where(arrival, $today)->count(),
            departures_today => Reservation::where(departure, $today)->count(),
            occupied_rooms => Reservation::where(status, checked_in)->count(),
            total_guests => Guest::count(),
            today_revenue => Journal::where(booking_date, $today)->where(direction, in)->sum(amount),
            pending_reservations => Reservation::where(status, pending)->count(),
        ];
        $recent_reservations = Reservation::with([guest, room])->orderBy(created_at, desc)->take(10)->get();
        return view(dashboard, compact(stats, recent_reservations));
    }
}

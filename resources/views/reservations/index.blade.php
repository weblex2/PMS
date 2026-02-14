<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservierungen - aiPms</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: "Segoe UI", system-ui, sans-serif; background: #f5f5f5; min-height: 100vh; }
        .sidebar { width: 250px; background: linear-gradient(180deg, #1e3a5f 0%, #0d2137 100%); color: white; padding: 20px 0; position: fixed; height: 100vh; overflow-y: auto; }
        .sidebar .nav-header { text-align: center; padding: 20px 10px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px; }
        .sidebar .nav-header h2 { color: #ffffff; margin: 0; font-size: 28px; font-weight: 700; letter-spacing: 1px; }
        .nav { padding: 0 10px; }
        .nav-link { display: flex; align-items: center; padding: 12px 15px; color: rgba(255,255,255,0.8); text-decoration: none; border-radius: 8px; margin-bottom: 5px; transition: all 0.3s; }
        .nav-link:hover { background: rgba(255,255,255,0.1); color: white; }
        .nav-link.active { background: rgba(255,255,255,0.2); color: white; }
        .nav-link i { width: 25px; font-size: 18px; }
        .main-content { margin-left: 250px; flex: 1; padding: 30px; }
        .card { background: white; border-radius: 12px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 25px; color: #1e3a5f; display: flex; justify-content: space-between; align-items: center; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: linear-gradient(135deg, #1e3a5f 0%, #0d2137 100%); border-radius: 8px; color: #fff; text-decoration: none; border: none; cursor: pointer; font-size: 14px; }
        .btn-sm { padding: 6px 10px; font-size: 12px; }
        .btn-edit { background: #6c757d; }
        .btn-delete { background: #dc3545; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; font-weight: 600; }
        .actions { display: flex; gap: 5px; }
        .badge { display: inline-flex; padding: 4px 10px; border-radius: 20px; font-size: 12px; }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-confirmed { background: #d4edda; color: #155724; }
        .badge-checked_in { background: #cce5ff; color: #004085; }
        .badge-checked_out { background: #d1ecf1; color: #0c5460; }
        .badge-cancelled { background: #f8d7da; color: #721c24; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    </style>
</head>
<body>
    <x-sidebar />
    <main class="main-content">
        <h1>Reservierungen <a href="/reservations/create" class="btn"><i class="fa fa-plus"></i> Neue Reservierung</a></h1>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <div class="card">
            @if($reservations->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Nr.</th>
                        <th>Gast</th>
                        <th>Zimmer</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Status</th>
                        <th>Preis</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $reservation)
                    <tr>
                        <td>{{ $reservation->reservation_number }}</td>
                        <td>{{ $reservation->guest->name }}</td>
                        <td>Nr. {{ $reservation->room->room_number }}</td>
                        <td>{{ $reservation->check_in->format('d.m.Y') }}</td>
                        <td>{{ $reservation->check_out->format('d.m.Y') }}</td>
                        <td>
                            <span class="badge badge-{{ $reservation->status }}">
                                @switch($reservation->status)
                                    @case('pending') Ausstehend @break
                                    @case('confirmed') Bestätigt @break
                                    @case('checked_in') Eingecheckt @break
                                    @case('checked_out') Ausgecheckt @break
                                    @case('cancelled') Storniert @break
                                @endswitch
                            </span>
                        </td>
                        <td>{{ number_format($reservation->total_price, 2, ',', '.') }} EUR</td>
                        <td class="actions">
                            <a href="/reservations/{{ $reservation->id }}/edit" class="btn btn-sm btn-edit"><i class="fa fa-pencil"></i></a>
                            <form action="/reservations/{{ $reservation->id }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm('Reservierung wirklich loeschen?');"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <p style="color: #666;">Keine Reservierungen vorhanden.</p>
            @endif
        </div>
    </main>
</body>
</html>

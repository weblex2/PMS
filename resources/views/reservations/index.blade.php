<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Reservierungen</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:system-ui;background:#f5f5f5;margin:0}
aside{width:250px;background:linear-gradient(180deg,#1e3a5f,#0d2137);color:white;padding:20px 0;position:fixed;height:100vh;overflow-y:auto}
aside h2{color:white;margin:0;font-size:28px;text-align:center}
aside .nav{padding:0 10px}
aside .nav-link{display:flex;align-items:center;padding:12px 15px;color:rgba(255,255,255,0.8);text-decoration:none;border-radius:8px;margin-bottom:5px;transition:all 0.3s}
aside .nav-link:hover{background:rgba(255,255,255,0.1);color:white}
main{margin-left:250px;flex:1;padding:30px}
.card{background:white;border-radius:12px;padding:25px;margin-bottom:20px;box-shadow:0 2px 10px rgba(0,0,0,0.1)}
h1{margin-bottom:25px;color:#1e3a5f}
.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:#1e3a5f;border-radius:6px;color:white;text-decoration:none;font-size:13px}
table{width:100%;border-collapse:collapse}
th,td{padding:12px;text-align:left;border-bottom:1px solid #eee}
th{background:#f8f9fa;color:#1e3a5f;font-weight:600}
tr:hover{background:#f8f9fa}
.badge{padding:4px 8px;border-radius:4px;font-size:12px}
.badge-pending{background:#fff3cd;color:#856404}
.badge-confirmed{background:#d4edda;color:#155724}
.badge-cancelled{background:#f8d7da;color:#721c24}
</style>
</head>
<body>
@include('components.sidebar')
<main>
<h1>Reservierungen <a href="/reservations/create" class="btn"><i class="fa fa-plus"></i> Neue Reservierung</a></h1>
<div class="card">
<table>
<thead>
<tr><th>Nr.</th><th>Gast</th><th>Zimmer</th><th>Check-in</th><th>Check-out</th><th>Preis</th><th>Status</th><th>Aktionen</th></tr>
</thead>
<tbody>
@forelse($reservations as $res)
<tr>
<td>{{ $res->reservation_number }}</td>
<td>{{ $res->guest->name }}</td>
<td>
@forelse($res->rooms as $room)
<span class="badge">{{ $room->room_number }}</span>
@empty
-
@endforelse
</td>
<td>{{ $res->check_in->format('d.m.Y') }}</td>
<td>{{ $res->check_out->format('d.m.Y') }}</td>
<td>{{ number_format($res->total_price,2,',','.') }} EUR</td>
<td><span class="badge badge-{{ $res->status }}>{{ $res->status }}</span></td>
<td><a href="/reservations/{{ $res->id }}/edit" class="btn btn-small"><i class="fa fa-edit"></i></a></td>
</tr>
@empty
<tr><td colspan="8" style="text-align:center">Keine Reservierungen gefunden</td></tr>
@endforelse
</tbody>
</table>
</div>
</main>
</body>
</html>
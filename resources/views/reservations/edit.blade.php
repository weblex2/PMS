<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Reservierung bearbeiten</title>
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
.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:#1e3a5f;border-radius:6px;color:white;border:none;cursor:pointer;font-size:13px}
.btn-small{padding:6px 12px;font-size:12px}
.form-group{margin-bottom:15px}
.form-group label{display:block;margin-bottom:5px;font-weight:500}
input,select,textarea{width:100%;padding:8px;border:1px solid #ddd;border-radius:6px}
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:15px}
.grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px}
.tabs{display:flex;border-bottom:2px solid #ddd;margin-bottom:20px}
.tab{padding:10px 20px;cursor:pointer;border:none;background:none;font-size:13px;color:#666;border-bottom:3px solid transparent}
.tab:hover{color:#1e3a5f}
.tab.active{color:#1e3a5f;border-bottom-color:#1e3a5f;font-weight:600}
.tab-content{display:none}
.tab-content.active{display:block}
.room-list{border:1px solid #eee;border-radius:8px;overflow:hidden;margin-top:15px}
.room-list-header{display:grid;grid-template-columns:40px 1fr 80px 80px 100px;padding:10px 15px;background:#f8f9fa;font-weight:600;color:#1e3a5f;border-bottom:2px solid #ddd;font-size:12px}
.room-list-item{display:grid;grid-template-columns:40px 1fr 80px 80px 100px;padding:10px 15px;border-bottom:1px solid #eee;align-items:center;cursor:pointer;font-size:13px}
.room-list-item:hover{background:#f8f9fa}
.room-list-item.selected{background:#d4edda}
.room-checkbox{width:16px;height:16px}
.room-info h4{margin:0 0 2px 0;color:#1e3a5f;font-size:14px}
.room-info p{margin:0;color:#666;font-size:11px}
.room-badge{padding:2px 8px;border-radius:10px;font-size:10px}
.badge-available{background:#d4edda;color:#155724}
.type-filters{display:flex;gap:8px;margin-bottom:15px;flex-wrap:wrap}
.type-filter{padding:5px 12px;border:1px solid #ddd;border-radius:15px;background:white;cursor:pointer;font-size:12px}
.type-filter.active{background:#1e3a5f;color:white;border-color:#1e3a5f}
.room-stats{display:flex;gap:15px;padding:12px;background:#f8f9fa;border-radius:8px;margin-bottom:15px}
.stat-item{text-align:center}
.stat-value{font-size:20px;font-weight:bold;color:#1e3a5f}
.stat-value.selected{color:#1e3a5f}
.stat-label{font-size:11px;color:#666}
.info-box{background:#f8f9fa;border-radius:8px;padding:15px}
.info-row{display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #eee;font-size:13px}
.info-label{color:#666}
.info-value{font-weight:600}
.alert{padding:12px;border-radius:8px;margin-bottom:15px}
.alert-danger{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb}
</style>
</head>
<body>
@include('components.sidebar')
<main>
<h1>Reservierung: {{ $reservation->reservation_number }}</h1>
@if($errors->any())
<div class="alert alert-danger">{{ $errors->first() }}</div>
@endif
<div class="card">
<div class="tabs">
<button type="button" class="tab active" onclick="showTab(this,'general')">Allgemein</button>
<button type="button" class="tab" onclick="showTab(this,'rooms')">Zimmer</button>
<button type="button" class="tab" onclick="showTab(this,'guest')">Gast</button>
<button type="button" class="tab" onclick="showTab(this,'payment')">Zahlung</button>
</div>
<form action="/reservations/{{ $reservation->id }}?t={{ time() }}" method="POST" id="resForm">
@csrf
@method('PUT')
<div id="tab-general" class="tab-content active">
<h3>Grunddaten</h3>
<div class="grid-2">
<div class="form-group"><label>Status</label><select name="status"><option value="pending" {{ $reservation->status=='pending'?'selected':'' }}>Ausstehend</option><option value="confirmed" {{ $reservation->status=='confirmed'?'selected':'' }}>Bestaetigt</option><option value="checked_in" {{ $reservation->status=='checked_in'?'selected':'' }}>Eingecheckt</option></select></div>
<div class="form-group"><label>Check-in *</label><input type="date" name="check_in" value="{{ $reservation->check_in->format('Y-m-d') }}" required></div>
</div>
<div class="grid-3">
<div class="form-group"><label>Check-out *</label><input type="date" name="check_out" value="{{ $reservation->check_out->format('Y-m-d') }}" required></div>
<div class="form-group"><label>Erwachsene</label><input type="number" name="adults" value="{{ $reservation->adults }}" min="1"></div>
<div class="form-group"><label>Kinder</label><input type="number" name="children" value="{{ $reservation->children }}" min="0"></div>
</div>
</div>
<div id="tab-rooms" class="tab-content">
<h3>Zimmer auswaehlen (mehrere moeglich)</h3>
<div class="room-stats">
<div class="stat-item"><div class="stat-value selected" id="statSelected">0</div><div class="stat-label">Ausgewaehlt</div></div>
</div>
<div class="type-filters">
<button type="button" class="type-filter active" onclick="filterRooms('all')">Alle</button>
<button type="button" class="type-filter" onclick="filterRooms('single')">1 Bett</button>
<button type="button" class="type-filter" onclick="filterRooms('double')">2 Betten</button>
<button type="button" class="type-filter" onclick="filterRooms('triple')">3 Betten</button>
<button type="button" class="type-filter" onclick="filterRooms('4bed')">4 Betten</button>
<button type="button" class="type-filter" onclick="filterRooms('5bed')">5 Betten</button>
<button type="button" class="type-filter" onclick="filterRooms('6bed')">6 Betten</button>
</div>
<div class="room-list">
<div class="room-list-header"><div></div><div>Zimmer</div><div>Typ</div><div>Preis</div><div>Status</div></div>
@php $resRoomIds = $reservation->rooms->pluck('id')->toArray() @endphp
@foreach($rooms->sortBy('room_number') as $room)
<div class="room-list-item {{ in_array($room->id, $resRoomIds) ? 'selected' : '' }}" data-type="{{ $room->type }}" data-id="{{ $room->id }}" onclick="toggleRoom('{{ $room->id }}')">
<div><input type="checkbox" class="room-checkbox" name="room_ids[]" value="{{ $room->id }}" {{ in_array($room->id, $resRoomIds) ? 'checked' : '' }} onclick="event.stopPropagation()"></div>
<div class="room-info"><h4>Nr. {{ $room->room_number }}</h4><p>{{ $room->type }}</p></div>
<div><span class="room-badge">{{ $room->bed_count }} Betten</span></div>
<div style="font-weight:600;color:#28a745">{{ number_format($room->price,2,',','.') }} EUR</div>
<div><span class="room-badge badge-available"><i class="fa fa-check"></i> Frei</span></div>
</div>
@endforeach
</div>
</div>
<div id="tab-guest" class="tab-content">
<h3>Gast</h3>
<div class="form-group"><label>Gast *</label><select name="guest_id" required>
@foreach($guests as $guest)<option value="{{ $guest->id }}" {{ $reservation->guest_id==$guest->id?'selected':'' }}>{{ $guest->name }}</option>@endforeach
</select></div>
<div class="info-box">
<div class="info-row"><span class="info-label">Name</span><span class="info-value">{{ $reservation->guest->name }}</span></div>
<div class="info-row"><span class="info-label">E-Mail</span><span class="info-value">{{ $reservation->guest->email }}</span></div>
</div>
</div>
<div id="tab-payment" class="tab-content">
<h3>Zahlung</h3>
<div class="grid-2">
<div class="form-group"><label>Status</label><select name="payment_status"><option value="pending" {{ $reservation->payment_status=='pending'?'selected':'' }}>Ausstehend</option><option value="paid" {{ $reservation->payment_status=='paid'?'selected':'' }}>Bezahlt</option></select></div>
<div class="form-group"><label>Preis</label><input type="text" value="{{ number_format($reservation->total_price,2,',','.') }} EUR" disabled></div>
</div>
</div>
<div style="margin-top:20px">
<button type="submit" class="btn" onclick="return validateForm()">Speichern</button>
<a href="/reservations" class="btn" style="background:#6c757d">Abbrechen</a>
</div>
</form>
</div>
</main>
<script>
function showTab(b,t){document.querySelectorAll('.tab-content').forEach(x=>x.classList.remove('active'));document.querySelectorAll('.tab').forEach(x=>x.classList.remove('active'));document.getElementById('tab-'+t).classList.add('active');if(b)b.classList.add('active');}
function toggleRoom(id){var r=document.querySelector('.room-list-item[data-id="'+id+'"]');var c=r.querySelector('.room-checkbox');if(r.classList.contains('selected')){r.classList.remove('selected');c.checked=false;}else{r.classList.add('selected');c.checked=true;}updateCount();}
function filterRooms(type){document.querySelectorAll('.type-filter').forEach(b=>b.classList.remove('active'));document.querySelector('.type-filter[onclick*="'+type+'"]').classList.add('active');document.querySelectorAll('.room-list-item').forEach(r=>{r.style.display=(type==='all'||r.dataset.type===type)?'':'none';});}
function updateCount(){var n=document.querySelectorAll('.room-list-item.selected .room-checkbox:checked').length;document.getElementById('statSelected').textContent=n;}
function validateForm(){var rooms=document.querySelectorAll('.room-checkbox:checked').length;if(rooms===0){alert('Bitte mindestens ein Zimmer auswaehlen');return false;}return true;}
document.addEventListener('DOMContentLoaded',function(){
  updateCount();
  document.getElementById('resForm').addEventListener('submit',function(e){
    var checked = document.querySelectorAll('.room-checkbox:checked');
    checked.forEach(function(c){
      var id = parseInt(c.value);
      if(isNaN(id)||id<1||id>31){c.checked=false;}
    });
    var remaining = document.querySelectorAll('.room-checkbox:checked').length;
    if(remaining===0){e.preventDefault();alert('Bitte mindestens ein gueltiges Zimmer auswaehlen');return false;}
  });
});
</script>
</body>
</html>
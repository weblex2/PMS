@extends('layouts.pms')
@section('content')

<h1>Reservierung: {{ $reservation->reservation_number }}</h1>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())
<div class="alert alert-danger">{{ $errors->first() }}</div>
@endif
<div class="card">
<div class="tabs">
<button type="button" class="tab active" onclick="showTab('general')">Allgemein</button>
<button type="button" class="tab" onclick="showTab('guest')">Gast</button>
<button type="button" class="tab" onclick="showTab('rooms')">Zimmer</button>
<button type="button" class="tab" onclick="showTab('paths')">Pfade</button>
<button type="button" class="tab" onclick="showTab('payment')">Zahlung</button>
</div>

<form action="/reservations/{{ $reservation->id }}" method="POST" id="resForm">
@csrf
@method('PUT')

{{-- General tab --}}
<div id="tab-general" class="tab-content active">
<h3>Grunddaten</h3>
<div class="grid-2">
<div class="form-group"><label>Status</label><select class="form-control" name="status"><option value="pending" {{ $reservation->status=='pending'?'selected':'' }}>Ausstehend</option><option value="confirmed" {{ $reservation->status=='confirmed'?'selected':'' }}>Bestaetigt</option><option value="checked_in" {{ $reservation->status=='checked_in'?'selected':'' }}>Eingecheckt</option><option value="checked_out" {{ $reservation->status=='checked_out'?'selected':'' }}>Ausgecheckt</option></select></div>
<div class="form-group"><label>Check-in *</label><input class="form-control" type="date" name="check_in" value="{{ $reservation->check_in->format('Y-m-d') }}" required></div>
</div>
<div class="grid-2">
<div class="form-group"><label>Match 1</label><input class="form-control" type="text" name="match1" value="{{ $reservation->match1 }}"></div>
<div class="form-group"><label>Match 2</label><input class="form-control" type="text" name="match2" value="{{ $reservation->match2 }}"></div>
</div>
<div class="grid-3">
<div class="form-group"><label>Check-out *</label><input class="form-control" type="date" name="check_out" value="{{ $reservation->check_out->format('Y-m-d') }}" required></div>
<div class="form-group"><label>Erwachsene</label><input class="form-control" type="number" name="adults" value="{{ $reservation->adults }}" min="1"></div>
<div class="form-group"><label>Kinder</label><input class="form-control" type="number" name="children" value="{{ $reservation->children }}" min="0"></div>
</div>
</div>

{{-- Guest tab --}}
<div id="tab-guest" class="tab-content">
<h3>Gast</h3>
<div class="form-group"><label>Gast *</label><select class="form-control" name="guest_id" required>
@foreach($guests as $guest)
<option value="{{ $guest->id }}" {{ $reservation->guest_id == $guest->id ? 'selected' : '' }}>{{ $guest->name }}</option>
@endforeach
</select></div>
</div>

{{-- Rooms tab --}}
<div id="tab-rooms" class="tab-content">
<h3>Zimmer zuordnen</h3>

{{-- Path tabs --}}
<div class="tabs" style="background:#f8f9fa;padding:10px;border-radius:8px;margin-bottom:15px;">
@foreach($reservation->paths as $index => $path)
<button type="button" class="room-tab tab {{ $index === 0 ? 'active' : '' }}" onclick="showRoomTab({{ $path->id }})" id="roomTab-{{ $path->id }}">
Pfad {{ $path->path_number }} ({{ $path->check_in->format('d.m.') }}-{{ $path->check_out->format('d.m.') }}) <span class="badge badge-success">{{ $path->rooms->count() }}</span>
</button>
@endforeach
</div>

@foreach($reservation->paths as $index => $path)
<div class="room-tab-content {{ $index === 0 ? 'active' : '' }}" id="roomContent-{{ $path->id }}" data-path="{{ $path->id }}">
<div class="room-list">
<div class="room-list-header"><div style="width:40px;"></div><div>Zimmer</div><div>Typ</div><div>Preis</div><div>Status</div></div>
@php $pathRoomIds = $path->rooms->pluck('id')->toArray(); @endphp
@foreach($rooms->sortBy('room_number') as $room)
@php $isSelected = in_array($room->id, $pathRoomIds); @endphp
<div class="room-list-item {{ $isSelected ? 'selected' : '' }}" data-type="{{ $room->type }}" data-id="{{ $room->id }}" onclick="toggleRoom(this)">
<div style="width:40px;"><input type="checkbox" class="room-checkbox" name="paths[{{ $path->id }}][room_ids][]" value="{{ $room->id }}" {{ $isSelected ? 'checked' : '' }} onclick="event.stopPropagation(); toggleRoom(this.parentElement.parentElement);"></div>
<div class="room-info"><h4>Nr. {{ $room->room_number }}</h4><p>{{ $room->type }}</p></div>
<div><span class="room-badge">{{ $room->bed_count }} Betten</span></div>
<div style="font-weight:600;color:#28a745">{{ number_format($room->price,2,',','.') }} EUR</div>
<div><span class="room-badge {{ $room->is_in_reservation ? 'badge-confirmed' : ($room->is_available ? 'badge-available' : 'badge-occupied') }}">{{ $room->is_in_reservation ? 'Zugewiesen' : ($room->is_available ? 'Frei' : 'Belegt') }}</span></div>
</div>
@endforeach
</div>
</div>
@endforeach
</div>

{{-- Paths tab --}}
<div id="tab-paths" class="tab-content">
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
<h3 style="margin:0;">Pfade verwalten</h3>
<button type="button" class="btn" onclick="addPath()">+ Neuer Pfad</button>
</div>

@foreach($reservation->paths as $path)
<div class="card" style="margin-bottom:15px;padding:15px;">
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
<h4 style="margin:0;">Pfad {{ $path->path_number }}</h4>
@if($reservation->paths->count() > 1)
<button type="button" class="btn btn-sm" style="background:#dc3545" onclick="deletePath({{ $path->id }})">Loeschen</button>
@endif
</div>
<div class="grid-2">
<div class="form-group"><label>Check-in</label><input class="form-control" type="date" name="paths[{{ $path->id }}][check_in]" value="{{ $path->check_in->format('Y-m-d') }}"></div>
<div class="form-group"><label>Check-out</label><input class="form-control" type="date" name="paths[{{ $path->id }}][check_out]" value="{{ $path->check_out->format('Y-m-d') }}"></div>
</div>
<div class="form-group"><label>Zimmer ({{ $path->rooms->count() }})</label>
<div style="display:flex;flex-wrap:wrap;gap:5px;">
@forelse($path->rooms as $room)
<span class="badge badge-confirmed" style="padding:5px 10px;font-size:14px;">{{ $room->room_number }}</span>
@empty
<span style="color:#666;">Keine Zimmer zugewiesen</span>
@endforelse
</div></div>
</div>
@endforeach
</div>

{{-- Payment tab --}}
<div id="tab-payment" class="tab-content">
<h3>Zahlung</h3>
<div class="grid-2">
<div class="form-group"><label>Status</label><select class="form-control" name="payment_status"><option value="pending" {{ $reservation->payment_status=='pending'?'selected':'' }}>Ausstehend</option><option value="paid" {{ $reservation->payment_status=='paid'?'selected':'' }}>Bezahlt</option></select></div>
<div class="form-group"><label>Preis</label><input class="form-control" type="text" value="{{ number_format($reservation->total_price,2,',','.') }} EUR" disabled style="background:#e9ecef;"></div>
</div>
</div>

<div style="margin-top:20px">
<button type="submit" class="btn">Speichern</button>
<a href="/reservations" class="btn" style="background:#6c757d">Abbrechen</a>
</div>
</form>
</div>

<style>
.room-tab { background:#fff;border:1px solid #dee2e6;padding:8px 15px;margin-right:5px;border-radius:5px;cursor:pointer; }
.room-tab.active { background:#007bff;color:#fff;border-color:#007bff; }
.room-tab .badge { margin-left:5px;background:rgba(255,255,255,0.3); }
.room-tab.active .badge { background:#28a745; }
.room-tab-content { display:none; }
.room-tab-content.active { display:block; }
.badge-confirmed { background:#28a745;color:#fff; }
.badge-available { background:#d4edda;color:#155724; }
.badge-occupied { background:#f8d7da;color:#721c24; }
</style>

<script>
function showTab(tab) {
  document.querySelectorAll('.tab-content').forEach(x => x.classList.remove('active'));
  document.querySelectorAll('.tab').forEach(x => x.classList.remove('active'));
  document.getElementById('tab-' + tab).classList.add('active');
  event.target.classList.add('active');
}
function showRoomTab(pathId) {
  document.querySelectorAll('.room-tab').forEach(x => x.classList.remove('active'));
  document.querySelectorAll('.room-tab-content').forEach(x => x.classList.remove('active'));
  document.getElementById('roomTab-' + pathId).classList.add('active');
  document.getElementById('roomContent-' + pathId).classList.add('active');
}
function toggleRoom(row) {
  var checkbox = row.querySelector('.room-checkbox');
  if (row.classList.contains('selected')) {
    row.classList.remove('selected');
    checkbox.checked = false;
  } else {
    row.classList.add('selected');
    checkbox.checked = true;
  }
}
function addPath() {
  if(!confirm('Neuen Pfad hinzufuegen?')) return;
  var f=document.getElementById('resForm');
  f.innerHTML+='<input type="hidden" name="add_path" value="1">';
  f.submit();
}
function deletePath(id) {
  if(!confirm('Pfad loeschen?')) return;
  var f=document.getElementById('resForm');
  f.innerHTML+='<input type="hidden" name="delete_path_'+id+'" value="1">';
  f.submit();
}
</script>
@endsection

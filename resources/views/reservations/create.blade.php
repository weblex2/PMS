@extends('layouts.pms')
@section('content')

<h1>Neue Reservierung</h1>
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
<div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="card">
<div class="tabs">
<button type="button" class="tab active" onclick="showTab(this,'general')">Allgemein</button>
<button type="button" class="tab" onclick="showTab(this,'guest')">Gast</button>
<button type="button" class="tab" onclick="showTab(this,'rooms')">Zimmer</button>
<button type="button" class="tab" onclick="showTab(this,'paths')">Pfade</button>
<button type="button" class="tab" onclick="showTab(this,'payment')">Zahlung</button>
</div>

<form action="/reservations" method="POST" id="resForm">
@csrf

<div id="tab-general" class="tab-content active">
<h3>Grunddaten</h3>
<div class="grid-2">
<div class="form-group"><label>Status</label>
<select class="form-control" name="status">
<option value="pending">Ausstehend</option>
<option value="confirmed">Bestaetigt</option>
</select></div>
<div class="form-group"><label>Check-in *</label><input type="date" name="check_in" value="{{ $checkIn }}" required></div>
</div>
<div class="grid-2">
<div class="form-group"><label>Match 1</label><input type="text" name="match1" maxlength="50"></div>
<div class="form-group"><label>Match 2</label><input type="text" name="match2" maxlength="50"></div>
</div>
<div class="grid-3">
<div class="form-group"><label>Check-out *</label><input type="date" name="check_out" value="{{ $checkOut }}" required></div>
<div class="form-group"><label>Erwachsene *</label><input type="number" name="adults" value="1" min="1" required></div>
<div class="form-group"><label>Kinder</label><input type="number" name="children" value="0" min="0"></div>
</div>
</div>

<div id="tab-guest" class="tab-content">
<h3>Gast</h3>
<div class="form-group"><label>Gast *</label>
<select class="form-control" name="guest_id" required>
<option value="">-- Gast waehlen --</option>
@foreach($guests as $guest)
<option value="{{ $guest->id }}">{{ $guest->name }} ({{ $guest->match1 ?? $guest->name }})</option>
@endforeach
</select></div>
</div>

<div id="tab-rooms" class="tab-content">
<h3>Zimmer zuordnen</h3>

<div class="tabs" style="background:#f8f9fa;padding:10px;border-radius:8px;margin-bottom:15px;">
<button type="button" class="room-tab tab active" onclick="showRoomTab(1)" id="roomTab-1">
Pfad 1 ({{ \Carbon\Carbon::parse($checkIn)->format('d.m.') }}-{{ \Carbon\Carbon::parse($checkOut)->format('d.m.') }}) <span class="badge badge-success" id="pathCount-1">0</span>
</button>
</div>

<div class="room-tab-content active" id="roomContent-1" data-path="1">
<?php 
$uniqueBedCounts = $allRooms->pluck('bed_count')->unique()->sort()->values();
?>
<div class="type-filters">
<button type="button" class="type-filter active" onclick="filterRoomsCreate('all', this, 1)">Alle</button>
@foreach($uniqueBedCounts as $bedCount)
<button type="button" class="type-filter" onclick="filterRoomsCreate('{{ $bedCount }}', this, 1)">{{ $bedCount }} {{ $bedCount == 1 ? 'Bett' : 'Betten' }}</button>
@endforeach
</div>
<div class="room-list">
<div class="room-list-header"><div style="width:40px;"></div><div>Zimmer</div><div>Typ</div><div>Preis</div><div>Status</div></div>
@foreach($allRooms->sortBy('room_number') as $room)
<div class="room-list-item" data-type="{{ $room->type }}" data-bed-count="{{ $room->bed_count }}" data-id="{{ $room->id }}" onclick="toggleRoomCreate(this, 1)">
<div style="width:40px;">
<input type="checkbox" class="room-checkbox" name="paths[1][room_ids][]" value="{{ $room->id }}" onclick="event.stopPropagation(); toggleRoomCreate(this.parentElement.parentElement, 1);">
</div>
<div class="room-info"><h4>Nr. {{ $room->room_number }}</h4><p>{{ $room->type }}</p></div>
<div><span class="room-badge">{{ $room->bed_count }} Betten</span></div>
<div style="font-weight:600;color:#28a745">{{ number_format($room->price,2,',','.') }} EUR</div>
<div>
<span class="room-badge {{ $room->is_available ? 'badge-available' : 'badge-occupied' }}">
{{ $room->is_available ? 'Frei' : 'Belegt' }}
</span>
</div>
</div>
@endforeach
</div>
</div>
</div>

<div id="tab-paths" class="tab-content">
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
<h3 style="margin:0;">Pfade verwalten</h3>
<button type="button" class="btn" onclick="addPath()">+ Neuer Pfad</button>
</div>

<div class="card" style="margin-bottom:15px;padding:15px;">
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
<h4 style="margin:0;">Pfad 1</h4>
</div>
<div class="grid-2">
<div class="form-group"><label>Check-in</label>
<input type="date" name="paths[1][check_in]" value="{{ $checkIn }}"></div>
<div class="form-group"><label>Check-out</label>
<input type="date" name="paths[1][check_out]" value="{{ $checkOut }}"></div>
</div>
<div class="form-group"><label>Zimmer (<span id="pathRoomCount-1">0</span>)</label>
<div style="display:flex;flex-wrap:wrap;gap:5px;" id="pathRooms-1">
<span style="color:#666;">Keine Zimmer zugewiesen</span>
</div>
</div>
</div>
</div>

<div id="tab-payment" class="tab-content">
<h3>Zahlung</h3>
<div class="grid-2">
<div class="form-group"><label>Status</label>
<select name="payment_status">
<option value="pending">Ausstehend</option>
<option value="paid">Bezahlt</option>
</select></div>
<div class="form-group"><label>Zahlungsart</label>
<select name="payment_method">
<option value="cash">Bar</option>
<option value="card">Karte</option>
</select></div>
</div>
<div class="form-group"><label>Notizen</label><textarea name="notes" rows="3"></textarea></div>
</div>

<div style="margin-top:20px">
<button type="submit" class="btn" onclick="return validateForm()">Reservierung anlegen</button>
<a href="/reservations" class="btn" style="background:#6c757d">Abbrechen</a>
</div>
</form>
</div>

<style>
.form-group { margin-bottom: 15px; }
.form-group label { display: block; margin-bottom: 5px; font-weight: 500; }
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; }
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
.grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; }
.alert { padding: 12px; border-radius: 6px; margin-bottom: 15px; }
.alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
.alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
.card { background: white; border-radius: 8px; padding: 20px; }
.tabs { display: flex; gap: 5px; margin-bottom: 20px; border-bottom: 1px solid #e5e7eb; }
.tab { padding: 10px 20px; background: none; border: none; border-bottom: 2px solid transparent; cursor: pointer; font-size: 14px; color: #6b7280; transition: all 0.3s; }
.tab:hover { color: #1e3a5f; }
.tab.active { color: #1e3a5f; border-bottom-color: #1e3a5f; font-weight: 500; }
.tab-content { display: none; }
.tab-content.active { display: block; }
.btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #1e3a5f; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; }
.btn:hover { background: #1a365d; }
.badge { display: inline-block; padding: 4px 8px; border-radius: 999px; font-size: 12px; }
.badge-success { background: #d1fae5; color: #065f46; }
.badge-available { background: #d1fae5; color: #065f46; }
.badge-occupied { background: #fee2e2; color: #991b1b; }
.badge-confirmed { background:#28a745;color:#fff; }
.room-tab { background:#fff;border:1px solid #dee2e6;padding:8px 15px;margin-right:5px;border-radius:5px;cursor:pointer; }
.room-tab.active { background:#007bff;color:#fff;border-color:#007bff; }
.room-tab .badge { margin-left:5px;background:rgba(255,255,255,0.3); }
.room-tab.active .badge { background:#28a745; }
.room-tab-content { display:none; }
.room-tab-content.active { display:block; }
.type-filters { display: flex; gap: 8px; margin-bottom: 15px; flex-wrap: wrap; }
.type-filter { padding: 6px 12px; background: #f3f4f6; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; }
.type-filter.active { background: #1e3a5f; color: white; }
.room-list { border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; }
.room-list-header { display: grid; grid-template-columns: 40px 1fr 100px 100px 100px; padding: 12px 15px; background: #f9fafb; font-weight: 600; font-size: 12px; color: #6b7280; border-bottom: 1px solid #e5e7eb; }
.room-list-item { display: grid; grid-template-columns: 40px 1fr 100px 100px 100px; padding: 12px 15px; align-items: center; border-bottom: 1px solid #f3f4f6; cursor: pointer; transition: background 0.2s; }
.room-list-item:hover { background: #f9fafb; }
.room-list-item.selected { background: #eff6ff; }
.room-info h4 { margin: 0; font-size: 14px; color: #1f2937; }
.room-info p { margin: 2px 0 0; font-size: 12px; color: #6b7280; }
.room-badge { display: inline-block; padding: 4px 8px; background: #e5e7eb; border-radius: 4px; font-size: 12px; }
</style>

<script>
function showTab(b,t){document.querySelectorAll('.tab-content').forEach(x=>x.classList.remove('active'));document.querySelectorAll('.tab').forEach(x=>x.classList.remove('active'));document.getElementById('tab-'+t).classList.add('active');if(b)b.classList.add('active');}
function showRoomTab(pathId){document.querySelectorAll('.room-tab').forEach(x=>x.classList.remove('active'));document.querySelectorAll('.room-tab-content').forEach(x=>x.classList.remove('active'));document.getElementById('roomTab-'+pathId).classList.add('active');document.getElementById('roomContent-'+pathId).classList.add('active');}
function toggleRoomCreate(row, pathId){var checkbox=row.querySelector('.room-checkbox');if(row.classList.contains('selected')){row.classList.remove('selected');checkbox.checked=false;}else{row.classList.add('selected');checkbox.checked=true;}updateCountCreate(pathId);}
function filterRoomsCreate(type,btn,pathId){document.querySelectorAll('#roomContent-'+pathId+' .type-filter').forEach(b=>b.classList.remove('active'));btn.classList.add('active');document.querySelectorAll('#roomContent-'+pathId+' .room-list-item').forEach(r=>{r.style.display=(type==='all'||r.dataset.bedCount===type)?'':'none';});}
function updateCountCreate(pathId){var n=document.querySelectorAll('#roomContent-'+pathId+' .room-checkbox:checked').length;var el=document.getElementById('statSelected-'+pathId);if(el)el.textContent=n;}
function addPath(){if(!confirm('Neuen Pfad hinzufuegen?'))return;var f=document.getElementById('resForm');f.innerHTML+='<input type="hidden" name="add_path" value="1">';f.submit();}
function validateForm(){var guest=document.querySelector('select[name="guest_id"]').value;var rooms=document.querySelectorAll('.room-checkbox:checked').length;if(!guest){alert('Bitte Gast auswaehlen');return false;}if(rooms===0){alert('Bitte mindestens ein Zimmer auswaehlen');return false;}return true;}
</script>
@endsection

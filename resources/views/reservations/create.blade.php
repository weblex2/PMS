<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Neue Reservierung</title>
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
.room-list-item.booked{background:#f8d7da;opacity:0.7}
.room-list-item.selected{background:#d4edda}
.room-checkbox{width:16px;height:16px}
.room-info h4{margin:0 0 2px 0;color:#1e3a5f;font-size:14px}
.room-info p{margin:0;color:#666;font-size:11px}
.room-badge{padding:2px 8px;border-radius:10px;font-size:10px}
.badge-available{background:#d4edda;color:#155724}
.badge-booked{background:#f8d7da;color:#721c24}
.type-filters{display:flex;gap:8px;margin-bottom:15px;flex-wrap:wrap}
.type-filter{padding:5px 12px;border:1px solid #ddd;border-radius:15px;background:white;cursor:pointer;font-size:12px}
.type-filter.active{background:#1e3a5f;color:white;border-color:#1e3a5f}
.room-stats{display:flex;gap:15px;padding:12px;background:#f8f9fa;border-radius:8px;margin-bottom:15px}
.stat-item{text-align:center}
.stat-value{font-size:20px;font-weight:bold;color:#1e3a5f}
.stat-value.available{color:#28a745}
.stat-value.booked{color:#dc3545}
.stat-value.selected{color:#1e3a5f}
.stat-label{font-size:11px;color:#666}
.modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1000}
.modal.active{display:flex}
.modal-content{background:white;border-radius:12px;width:90%;max-width:600px;max-height:80vh;margin:auto}
.modal-header{padding:15px 20px;border-bottom:1px solid #eee;display:flex;justify-content:space-between;align-items:center}
.modal-body{padding:20px;overflow-y:auto}
.guest-item{border:1px solid #eee;border-radius:8px;padding:12px;cursor:pointer;display:flex;align-items:center;gap:12px}
.guest-item:hover{background:#f8f9fa;border-color:#1e3a5f}
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
<h1>Neue Reservierung</h1>
@if($errors->any())
<div class="alert alert-danger">{{ $errors->first() }}</div>
@endif
<div class="card">
<div class="tabs">
<button type="button" class="tab active" onclick="showTab(this,'general')">Allgemein</button>
<button type="button" class="tab" onclick="showTab(this,'guest')">Gast</button>
<button type="button" class="tab" onclick="showTab(this,'rooms')">Zimmer</button>
<button type="button" class="tab" onclick="showTab(this,'payment')">Zahlung</button>
</div>
<form action="/reservations" method="POST" id="resForm">
@csrf
<div id="tab-general" class="tab-content active">
<h3>Grunddaten</h3>
<div class="grid-2">
<div class="form-group"><label>Check-in *</label><input type="date" name="check_in" value="{{ $checkIn }}" required></div>
<div class="form-group"><label>Check-out *</label><input type="date" name="check_out" value="{{ $checkOut }}" required></div>
</div>
<div class="grid-2">
<div class="form-group"><label>Match 1</label><input type="text" name="match1" maxlength="50"></div>
<div class="form-group"><label>Match 2</label><input type="text" name="match2" maxlength="50"></div>
</div>
<div class="grid-3">
<div class="form-group"><label>Erwachsene *</label><input type="number" name="adults" value="1" min="1" required></div>
<div class="form-group"><label>Kinder</label><input type="number" name="children" value="0" min="0"></div>
<div class="form-group"><label>Art *</label><select name="reservation_type"><option value="standard">Standard</option><option value="group">Gruppe</option></select></div>
</div>
<div class="form-group"><label>Gast *</label>
<div id="guestSelectionArea"><button type="button" class="btn" onclick="openGuestModal()"><i class="fa fa-search"></i> Gast suchen</button></div>
<input type="hidden" name="guest_id" id="selectedGuestId" required>
</div>
</div>
<div id="tab-guest" class="tab-content">
<h3>Gastdaten</h3>
<div class="info-box">
<div class="info-row"><span class="info-label">Name</span><span class="info-value" id="guestName">-</span></div>
<div class="info-row"><span class="info-label">E-Mail</span><span class="info-value" id="guestEmail">-</span></div>
</div>
<button type="button" class="btn" onclick="openGuestModal()"><i class="fa fa-search"></i> Gast aendern</button>
</div>
<div id="tab-rooms" class="tab-content">
<h3>Zimmer auswaehlen</h3>
<div class="room-stats">
<div class="stat-item"><div class="stat-value available">{{ $allRooms->where('is_available',true)->count() }}</div><div class="stat-label">Verfuegbar</div></div>
<div class="stat-item"><div class="stat-value booked">{{ $allRooms->where('is_booked',true)->count() }}</div><div class="stat-label">Belegt</div></div>
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
@forelse($allRooms->sortBy('room_number') as $room)
<div class="room-list-item {{ $room->is_booked ? 'booked' : '' }}" data-type="{{ $room->type }}" data-id="{{ $room->id }}" onclick="toggleRoom('{{ $room->id }}','{{ $room->is_available }}')">
<div>@if($room->is_available)<input type="checkbox" class="room-checkbox" name="room_ids[]" value="{{ $room->id }}" onclick="event.stopPropagation()">@else<i class="fa fa-lock"></i>@endif</div>
<div class="room-info"><h4>Nr. {{ $room->room_number }}</h4><p>{{ $room->type }}</p></div>
<div><span class="room-badge">{{ $room->bed_count }} Betten</span></div>
<div style="font-weight:600;color:#28a745">{{ number_format($room->price,2,',','.') }} EUR</div>
<div>@if($room->is_booked)<span class="room-badge badge-booked"><i class="fa fa-ban"></i> Belegt</span>@else<span class="room-badge badge-available"><i class="fa fa-check"></i> Frei</span>@endif</div>
</div>
@empty
<div style="padding:20px;text-align:center;color:#666">Keine Zimmer</div>
@endforelse
</div>
</div>
<div id="tab-payment" class="tab-content">
<h3>Zahlung</h3>
<div class="grid-2">
<div class="form-group"><label>Zahlungsart</label><select name="payment_method"><option value="cash">Bar</option><option value="card">Karte</option></select></div>
<div class="form-group"><label>Status</label><select name="payment_status"><option value="pending">Ausstehend</option><option value="paid">Bezahlt</option></select></div>
</div>
<div class="form-group"><label>Notizen</label><textarea name="notes" rows="3"></textarea></div>
</div>
<div style="margin-top:20px">
<button type="submit" class="btn" onclick="return validateForm()">Reservierung anlegen</button>
<a href="/reservations" class="btn" style="background:#6c757d">Abbrechen</a>
</div>
</form>
</div>
</main>
<div class="modal" id="guestModal">
<div class="modal-content">
<div class="modal-header"><h3>Gast suchen</h3><button type="button" onclick="closeGuestModal()" style="background:none;border:none;font-size:24px;cursor:pointer">&times;</button></div>
<div class="modal-body">
<input type="text" id="guestSearchInput" placeholder="Name..." oninput="searchGuests()" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;margin-bottom:10px">
<div id="guestList"></div>
</div>
</div>
</div>
<script>
const guestsData = {!! $guestsJson !!};
let selectedGuest = null;

function showTab(b,t){document.querySelectorAll('.tab-content').forEach(x=>x.classList.remove('active'));document.querySelectorAll('.tab').forEach(x=>x.classList.remove('active'));document.getElementById('tab-'+t).classList.add('active');if(b)b.classList.add('active');}
function toggleRoom(id,avail){if(avail==='false')return;var r=document.querySelector('.room-list-item[data-id="'+id+'"]');var c=r.querySelector('.room-checkbox');if(r.classList.contains('selected')){r.classList.remove('selected');c.checked=false;}else{r.classList.add('selected');c.checked=true;}updateCount();}
function filterRooms(type){document.querySelectorAll('.type-filter').forEach(b=>b.classList.remove('active'));document.querySelector('.type-filter[onclick*="'+type+'"]').classList.add('active');document.querySelectorAll('.room-list-item').forEach(r=>{r.style.display=(type==='all'||r.dataset.type===type)?'':'none';});}
function updateCount(){var n=document.querySelectorAll('.room-list-item.selected .room-checkbox:checked').length;document.getElementById('statSelected').textContent=n;}
function openGuestModal(){document.getElementById('guestModal').classList.add('active');renderGuestList(guestsData);}
function closeGuestModal(){document.getElementById('guestModal').classList.remove('active');}
function renderGuestList(list){var d=document.getElementById('guestList');if(list.length===0){d.innerHTML='<p>Keine Gaeste</p>';return;}d.innerHTML=list.map(g=>'<div class="guest-item" onclick="selectGuest('+g.id+')"><div style="width:36px;height:36px;background:#1e3a5f;color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:bold">'+g.name.charAt(0)+'</div><div><h4>'+g.name+'</h4><p>'+(g.email||'-')+'</p></div></div>').join('');}
function searchGuests(){var q=document.getElementById('guestSearchInput').value.toLowerCase();var f=guestsData.filter(g=>(g.name).toLowerCase().includes(q));renderGuestList(f);}
function selectGuest(id){var g=guestsData.find(x=>x.id===id);if(!g)return;selectedGuest=g;document.getElementById('selectedGuestId').value=g.id;document.getElementById('guestSelectionArea').innerHTML='<div style="background:#d4edda;padding:10px;border-radius:6px"><b>'+g.name+'</b> ausgewaehlt</div>';document.getElementById('guestName').textContent=g.name;document.getElementById('guestEmail').textContent=g.email||'-';closeGuestModal();}
function validateForm(){var guest=document.getElementById('selectedGuestId').value;var rooms=document.querySelectorAll('.room-checkbox:checked').length;if(!guest){alert('Bitte Gast auswaehlen');return false;}if(rooms===0){alert('Bitte mindestens ein Zimmer auswaehlen');return false;}return true;}
document.getElementById('guestModal').addEventListener('click',function(e){if(e.target===this)closeGuestModal();});
</script>
</body>
</html>
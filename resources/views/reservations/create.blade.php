<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neue Reservierung - aiPms</title>
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
        .main-content { margin-left: 250px; flex: 1; padding: 30px; }
        .card { background: white; border-radius: 12px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 25px; color: #1e3a5f; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: linear-gradient(135deg, #1e3a5f 0%, #0d2137 100%); border-radius: 8px; color: #fff; text-decoration: none; border: none; cursor: pointer; font-size: 14px; }
        .btn-secondary { background: #6c757d; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        /* Tabs */
        .tabs { display: flex; border-bottom: 2px solid #ddd; margin-bottom: 25px; }
        .tab { 
            padding: 12px 25px; 
            cursor: pointer; 
            border: none; 
            background: none; 
            font-size: 14px; 
            color: #666;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            transition: all 0.3s;
        }
        .tab:hover { color: #1e3a5f; }
        .tab.active { 
            color: #1e3a5f; 
            border-bottom-color: #1e3a5f; 
            font-weight: 600;
        }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        
        /* Room Selection */
        .room-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; margin-top: 10px; }
        .room-card {
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }
        .room-card:hover { border-color: #1e3a5f; background: #f8f9fa; }
        .room-card.selected { border-color: #28a745; background: #d4edda; }
        .room-card input[type="checkbox"] { display: none; }
        .room-number { font-size: 18px; font-weight: bold; color: #1e3a5f; }
        .room-type { font-size: 12px; color: #666; }
        .room-price { font-size: 14px; color: #28a745; font-weight: 600; margin-top: 5px; }
        .room-status { font-size: 11px; padding: 2px 8px; border-radius: 10px; margin-top: 5px; display: inline-block; }
        .status-free { background: #d4edda; color: #155724; }
        .status-occupied { background: #f8d7da; color: #721c24; }
        
        /* Info Box */
        .info-box { background: #f8f9fa; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #666; }
        .info-value { font-weight: 600; }
        
        .hidden-field { display: none; }
    </style>
</head>
<body>
    @include('components.sidebar')
    <main class="main-content">
        <h1>Neue Reservierung anlegen</h1>
        
        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="card">
            <!-- Tabs -->
            <div class="tabs">
                <button type="button" class="tab active" onclick="showTab('general')">Allgemein</button>
                <button type="button" class="tab" onclick="showTab('guest')">Gast</button>
                <button type="button" class="tab" onclick="showTab('payment')">Zahlung</button>
                <button type="button" class="tab" onclick="showTab('summary')">Zusammenfassung</button>
            </div>
            
            <form action="/reservations" method="POST" id="reservationForm">
                @csrf
                
                <!-- Tab 1: Allgemein -->
                <div id="tab-general" class="tab-content active">
                    <h3>Grunddaten</h3>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Check-in *</label>
                            <input type="date" name="check_in" value="{{ old('check_in', date('Y-m-d')) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Check-out *</label>
                            <input type="date" name="check_out" value="{{ old('check_out', date('Y-m-d', strtotime('+3 days'))) }}" required>
                        </div>
                    </div>
                    
                    <div class="grid-3">
                        <div class="form-group">
                            <label>Erwachsene *</label>
                            <input type="number" name="adults" value="{{ old('adults', 1) }}" min="1" required>
                        </div>
                        <div class="form-group">
                            <label>Kinder</label>
                            <input type="number" name="children" value="{{ old('children', 0) }}" min="0">
                        </div>
                        <div class="form-group">
                            <label>Reservierungsart *</label>
                            <select name="reservation_type" required>
                                <option value="standard">Standard</option>
                                <option value="group">Gruppe</option>
                                <option value="business">Geschäftlich</option>
                                <option value="event">Veranstaltung</option>
                            </select>
                        </div>
                    </div>
                    
                    <h3 style="margin-top: 25px;">Zimmerauswahl</h3>
                    <p style="color: #666; margin-bottom: 15px;">Wählen Sie ein oder mehrere Zimmer aus:</p>
                    
                    <div class="room-grid">
                        @forelse($rooms as $room)
                        <label class="room-card" onclick="toggleRoom(this)">
                            <input type="checkbox" name="room_ids[]" value="{{ $room->id }}" 
                                   data-price="{{ $room->price }}" data-number="{{ $room->room_number }}">
                            <div class="room-number">Nr. {{ $room->room_number }}</div>
                            <div class="room-type">{{ $room->type }}</div>
                            <div class="room-price">{{ number_format($room->price, 2, ',', '.') }} EUR</div>
                            <div class="room-status status-{{ $room->status }}">
                                @if($room->status == 'free') Frei
                                @elseif($room->status == 'occupied') Belegt
                                @else Reinigung @endif
                            </div>
                        </label>
                        @empty
                        <p style="color: #666;">Keine Zimmer verfügbar.</p>
                        @endforelse
                    </div>
                </div>
                
                <!-- Tab 2: Gast -->
                <div id="tab-guest" class="tab-content">
                    <h3>Gastdaten</h3>
                    <div class="form-group">
                        <label>Gast auswählen *</label>
                        <select name="guest_id" id="guestSelect" required onchange="updateGuestInfo()">
                            <option value="">-- Gast auswählen --</option>
                            @foreach($guests as $guest)
                                <option value="{{ $guest->id }}" 
                                        data-name="{{ $guest->name }}"
                                        data-email="{{ $guest->email }}"
                                        data-phone="{{ $guest->phone ?? '-' }}"
                                        data-address="{{ $guest->address ?? '-' }}"
                                        data-city="{{ $guest->city ?? '' }}">
                                    {{ $guest->name }} - {{ $guest->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="info-box" id="guestInfoBox" style="display: none;">
                        <div class="info-row">
                            <span class="info-label">Name</span>
                            <span class="info-value" id="guestName">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">E-Mail</span>
                            <span class="info-value" id="guestEmail">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Telefon</span>
                            <span class="info-value" id="guestPhone">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Adresse</span>
                            <span class="info-value" id="guestAddress">-</span>
                        </div>
                    </div>
                    
                    <p style="color: #666; margin-top: 15px;">Oder <a href="/guests/create" style="color: #1e3a5f;">neuen Gast anlegen</a></p>
                </div>
                
                <!-- Tab 3: Zahlung -->
                <div id="tab-payment" class="tab-content">
                    <h3>Zahlungsdaten</h3>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Zahlungsart *</label>
                            <select name="payment_method" required>
                                <option value="cash">Bar</option>
                                <option value="card">Karte</option>
                                <option value="transfer">Überweisung</option>
                                <option value="online">Online</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Zahlungsstatus *</label>
                            <select name="payment_status" required>
                                <option value="pending">Ausstehend</option>
                                <option value="paid">Bezahlt</option>
                                <option value="advance">Anzahlung</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Anzahlung (EUR)</label>
                        <input type="number" name="advance_payment" value="{{ old('advance_payment', 0) }}" step="0.01" min="0">
                    </div>
                    
                    <div class="form-group">
                        <label>Notizen</label>
                        <textarea name="notes" rows="4" placeholder="Zusätzliche Notizen zur Reservierung...">{{ old('notes') }}</textarea>
                    </div>
                </div>
                
                <!-- Tab 4: Zusammenfassung -->
                <div id="tab-summary" class="tab-content">
                    <h3>Reservierungsübersicht</h3>
                    <div class="info-box">
                        <div class="info-row">
                            <span class="info-label">Anreise</span>
                            <span class="info-value" id="summaryCheckIn">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Abreise</span>
                            <span class="info-value" id="summaryCheckOut">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Nächte</span>
                            <span class="info-value" id="summaryNights">0</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Gäste</span>
                            <span class="info-value" id="summaryGuests">-</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Zimmer</span>
                            <span class="info-value" id="summaryRooms">-</span>
                        </div>
                        <div class="info-row" style="font-size: 18px; font-weight: bold; color: #28a745;">
                            <span class="info-label">Gesamtpreis</span>
                            <span class="info-value" id="summaryPrice">0,00 EUR</span>
                        </div>
                    </div>
                    
                    <div id="selectedRoomsList" style="margin-top: 20px;"></div>
                </div>
                
                <div style="margin-top: 25px;">
                    <button type="submit" class="btn">Reservierung anlegen</button>
                    <a href="/reservations" class="btn btn-secondary">Abbrechen</a>
                </div>
            </form>
        </div>
    </main>
    
    <script>
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.getElementById('tab-' + tabName).classList.add('active');
            event.target.classList.add('active');
            
            if (tabName === 'summary') {
                updateSummary();
            }
        }
        
        function toggleRoom(element) {
            element.classList.toggle('selected');
            element.querySelector('input').checked = element.classList.contains('selected');
        }
        
        function updateGuestInfo() {
            const select = document.getElementById('guestSelect');
            const option = select.options[select.selectedIndex];
            const infoBox = document.getElementById('guestInfoBox');
            
            if (select.value) {
                infoBox.style.display = 'block';
                document.getElementById('guestName').textContent = option.dataset.name || '-';
                document.getElementById('guestEmail').textContent = option.dataset.email || '-';
                document.getElementById('guestPhone').textContent = option.dataset.phone || '-';
                document.getElementById('guestAddress').textContent = (option.dataset.address + ' ' + option.dataset.city).trim() || '-';
            } else {
                infoBox.style.display = 'none';
            }
        }
        
        function updateSummary() {
            const checkIn = new Date(document.querySelector('input[name="check_in"]').value);
            const checkOut = new Date(document.querySelector('input[name="check_out"]').value);
            const adults = document.querySelector('input[name="adults"]').value;
            const children = document.querySelector('input[name="children"]').value;
            
            const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
            const selectedRooms = document.querySelectorAll('.room-card.selected');
            
            let totalPrice = 0;
            let roomsHtml = '';
            
            selectedRooms.forEach(room => {
                const input = room.querySelector('input');
                const price = parseFloat(input.dataset.price) * nights;
                totalPrice += price;
                roomsHtml += '<div style="padding: 10px; background: #f8f9fa; border-radius: 5px; margin-bottom: 5px;">';
                roomsHtml += '<strong>Zimmer ' + input.dataset.number + '</strong>: ' + price.toFixed(2).replace('.', ',') + ' EUR (' + nights + ' x ' + parseFloat(input.dataset.price).toFixed(2).replace('.', ',') + ' EUR)';
                roomsHtml += '</div>';
            });
            
            document.getElementById('summaryCheckIn').textContent = checkIn.toLocaleDateString('de-DE');
            document.getElementById('summaryCheckOut').textContent = checkOut.toLocaleDateString('de-DE');
            document.getElementById('summaryNights').textContent = nights;
            document.getElementById('summaryGuests').textContent = adults + ' Erw., ' + children + ' Kind.';
            document.getElementById('summaryRooms').textContent = selectedRooms.length + ' Zimmer';
            document.getElementById('summaryPrice').textContent = totalPrice.toFixed(2).replace('.', ',') + ' EUR';
            document.getElementById('selectedRoomsList').innerHTML = roomsHtml || '<p style="color: #666;">Keine Zimmer ausgewählt.</p>';
        }
        
        // Update summary when inputs change
        document.querySelector('input[name="check_in"]').addEventListener('change', updateSummary);
        document.querySelector('input[name="check_out"]').addEventListener('change', updateSummary);
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservierung bearbeiten - aiPms</title>
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
        h3 { margin-bottom: 15px; color: #1e3a5f; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: linear-gradient(135deg, #1e3a5f 0%, #0d2137 100%); border-radius: 8px; color: #fff; text-decoration: none; border: none; cursor: pointer; font-size: 14px; }
        .btn-secondary { background: #6c757d; }
        .btn-success { background: #28a745; }
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
        
        /* Info Box */
        .info-box { background: #f8f9fa; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #666; }
        .info-value { font-weight: 600; }
    </style>
</head>
<body>
    @include('components.sidebar')
    <main class="main-content">
        <h1>Reservierung bearbeiten: {{ $reservation->reservation_number }}</h1>
        
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
                <button class="tab active" onclick="showTab('general')">Allgemein</button>
                <button class="tab" onclick="showTab('guest')">Gast</button>
                <button class="tab" onclick="showTab('payment')">Zahlung</button>
                <button class="tab" onclick="showTab('history')">Verlauf</button>
            </div>
            
            <form action="/reservations/{{ $reservation->id }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Tab 1: Allgemein -->
                <div id="tab-general" class="tab-content active">
                    <h3>Grunddaten</h3>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Reservierungsnummer</label>
                            <input type="text" value="{{ $reservation->reservation_number }}" disabled style="background: #f5f5f5;">
                        </div>
                        <div class="form-group">
                            <label>Status *</label>
                            <select name="status" required>
                                <option value="pending" {{ $reservation->status == 'pending' ? 'selected' : '' }}>Ausstehend</option>
                                <option value="confirmed" {{ $reservation->status == 'confirmed' ? 'selected' : '' }}>Bestätigt</option>
                                <option value="checked_in" {{ $reservation->status == 'checked_in' ? 'selected' : '' }}>Eingecheckt</option>
                                <option value="checked_out" {{ $reservation->status == 'checked_out' ? 'selected' : '' }}>Ausgecheckt</option>
                                <option value="cancelled" {{ $reservation->status == 'cancelled' ? 'selected' : '' }}>Storniert</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid-3">
                        <div class="form-group">
                            <label>Check-in *</label>
                            <input type="date" name="check_in" value="{{ $reservation->check_in->format('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Check-out *</label>
                            <input type="date" name="check_out" value="{{ $reservation->check_out->format('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Zimmer *</label>
                            <select name="room_id" required>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ $reservation->room_id == $room->id ? 'selected' : '' }}>
                                        Nr. {{ $room->room_number }} - {{ $room->type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Erwachsene *</label>
                            <input type="number" name="adults" value="{{ $reservation->adults }}" min="1" required>
                        </div>
                        <div class="form-group">
                            <label>Kinder</label>
                            <input type="number" name="children" value="{{ $reservation->children }}" min="0">
                        </div>
                    </div>
                </div>
                
                <!-- Tab 2: Gast -->
                <div id="tab-guest" class="tab-content">
                    <h3>Gastdaten</h3>
                    <div class="form-group">
                        <label>Gast auswählen *</label>
                        <select name="guest_id" required>
                            @foreach($guests as $guest)
                                <option value="{{ $guest->id }}" {{ $reservation->guest_id == $guest->id ? 'selected' : '' }}>
                                    {{ $guest->name }} - {{ $guest->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="info-box">
                        <div class="info-row">
                            <span class="info-label">Name</span>
                            <span class="info-value">{{ $reservation->guest->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">E-Mail</span>
                            <span class="info-value">{{ $reservation->guest->email }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Telefon</span>
                            <span class="info-value">{{ $reservation->guest->phone ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Adresse</span>
                            <span class="info-value">{{ $reservation->guest->address ?? '-' }}, {{ $reservation->guest->city ?? '' }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Tab 3: Zahlung -->
                <div id="tab-payment" class="tab-content">
                    <h3>Zahlungsdaten</h3>
                    <div class="grid-2">
                        <div class="form-group">
                            <label>Zahlungsstatus *</label>
                            <select name="payment_status" required>
                                <option value="pending" {{ $reservation->payment_status == 'pending' ? 'selected' : '' }}>Ausstehend</option>
                                <option value="paid" {{ $reservation->payment_status == 'paid' ? 'selected' : '' }}>Bezahlt</option>
                                <option value="refunded" {{ $reservation->payment_status == 'refunded' ? 'selected' : '' }}>Erstattet</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Gesamtpreis (EUR)</label>
                            <input type="text" value="{{ number_format($reservation->total_price, 2, ',', '.') }}" disabled style="background: #f5f5f5; font-size: 18px; font-weight: bold; color: #28a745;">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Notizen</label>
                        <textarea name="notes" rows="4" placeholder="Zusätzliche Notizen zur Reservierung...">{{ $reservation->notes }}</textarea>
                    </div>
                </div>
                
                <!-- Tab 4: Verlauf -->
                <div id="tab-history" class="tab-content">
                    <h3>Reservierungsverlauf</h3>
                    <div class="info-box">
                        <div class="info-row">
                            <span class="info-label">Erstellt am</span>
                            <span class="info-value">{{ $reservation->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Zuletzt geändert</span>
                            <span class="info-value">{{ $reservation->updated_at->format('d.m.Y H:i') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Zimmer</span>
                            <span class="info-value">Nr. {{ $reservation->room->room_number }} ({{ $reservation->room->type }})</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Aufenthaltsdauer</span>
                            <span class="info-value">{{ $reservation->check_in->diffInDays($reservation->check_out) }} Nächte</span>
                        </div>
                    </div>
                </div>
                
                <div style="margin-top: 25px;">
                    <button type="submit" class="btn">Änderungen speichern</button>
                    <a href="/reservations" class="btn btn-secondary">Abbrechen</a>
                </div>
            </form>
        </div>
    </main>
    
    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById('tab-' + tabName).classList.add('active');
            
            // Update button state
            event.target.classList.add('active');
        }
    </script>
</body>
</html>

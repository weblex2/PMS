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
    </style>
</head>
<body>
    <x-sidebar />
    <main class="main-content">
        <h1>Neue Reservierung</h1>
        
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
            <form action="/reservations" method="POST">
                @csrf
                
                <h3 style="margin-bottom: 15px; color: #1e3a5f;">Gast & Zimmer</h3>
                <div class="grid-2">
                    <div class="form-group">
                        <label>Gast *</label>
                        <select name="guest_id" required>
                            <option value="">-- Gast auswählen --</option>
                            @foreach($guests as $guest)
                                <option value="{{ $guest->id }}">{{ $guest->name }} ({{ $guest->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Zimmer *</label>
                        <select name="room_id" required>
                            <option value="">-- Zimmer auswählen --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">Nr. {{ $room->room_number }} - {{ $room->type }} ({{ number_format($room->price, 2, ',', '.') }} EUR)</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <h3 style="margin: 25px 0 15px; color: #1e3a5f;">Datum & Gäste</h3>
                <div class="grid-3">
                    <div class="form-group">
                        <label>Check-in *</label>
                        <input type="date" name="check_in" value="{{ old('check_in') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Check-out *</label>
                        <input type="date" name="check_out" value="{{ old('check_out') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Erwachsene *</label>
                        <input type="number" name="adults" value="{{ old('adults', 1) }}" min="1" required>
                    </div>
                </div>
                <div class="form-group" style="max-width: 200px;">
                    <label>Kinder</label>
                    <input type="number" name="children" value="{{ old('children', 0) }}" min="0">
                </div>
                
                <div style="margin-top: 30px;">
                    <button type="submit" class="btn">Reservierung anlegen</button>
                    <a href="/reservations" class="btn btn-secondary">Abbrechen</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>

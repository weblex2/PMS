<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zimmer bearbeiten - aiPms</title>
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
        h1 { margin-bottom: 25px; color: #1e3a5f; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; color: #333; font-weight: 500; }
        input, select { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
        input:focus, select:focus { outline: none; border-color: #1e3a5f; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 12px 20px; background: linear-gradient(135deg, #1e3a5f 0%, #0d2137 100%); border-radius: 8px; color: #fff; text-decoration: none; border: none; cursor: pointer; font-size: 14px; }
        .btn-secondary { background: #6c757d; }
        .btn-group { display: flex; gap: 10px; margin-top: 30px; }
    </style>
</head>
<body>
    <x-sidebar />
    <main class="main-content">
        <h1>Zimmer bearbeiten</h1>
        <div class="card">
            <form action="/rooms/{{ $room->id }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Zimmernummer</label>
                    <input type="text" name="room_number" value="{{ $room->room_number }}" required>
                </div>
                <div class="form-group">
                    <label>Typ</label>
                    <select name="type" required>
                        <option value="Standard" {{ $room->type == 'Standard' ? 'selected' : '' }}>Standard</option>
                        <option value="Komfort" {{ $room->type == 'Komfort' ? 'selected' : '' }}>Komfort</option>
                        <option value="Suite" {{ $room->type == 'Suite' ? 'selected' : '' }}>Suite</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Preis pro Nacht (EUR)</label>
                    <input type="number" name="price" step="0.01" value="{{ $room->price }}" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="free" {{ $room->status == 'free' ? 'selected' : '' }}>Frei</option>
                        <option value="occupied" {{ $room->status == 'occupied' ? 'selected' : '' }}>Belegt</option>
                        <option value="cleaning" {{ $room->status == 'cleaning' ? 'selected' : '' }}>Reinigung</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Beschreibung</label>
                    <textarea name="description" rows="3">{{ $room->description }}</textarea>
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn"><i class="fa fa-save"></i> Speichern</button>
                    <a href="/rooms" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Abbrechen</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>

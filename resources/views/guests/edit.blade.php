<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gast bearbeiten - PMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: linear-gradient(180deg, #1e3a5f 0%, #0d2137 100%); color: white; padding: 20px 0; position: fixed; height: 100vh; overflow-y: auto; }
        .nav { padding: 0 10px; }
        .nav-link { display: flex; align-items: center; padding: 12px 15px; color: rgba(255,255,255,0.8); text-decoration: none; border-radius: 8px; margin-bottom: 5px; transition: all 0.3s; }
        .nav-link:hover { background: rgba(255,255,255,0.1); color: white; }
        .nav-link i { width: 25px; font-size: 18px; }
        .main-content { margin-left: 250px; flex: 1; padding: 30px; }
        .card { background: white; border-radius: 12px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 25px; color: #1e3a5f; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #4a5568; }
        input, select, textarea { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: border-color 0.3s; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #1e3a5f; }
        .btn { padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .btn-primary { background: #1e3a5f; color: white; }
        .btn-primary:hover { background: #2c5282; }
        .btn-secondary { background: #718096; color: white; }
        .btn-secondary:hover { background: #4a5568; }
        .error { color: #e53e3e; font-size: 12px; margin-top: 5px; }
        .error-message { background: #fed7d7; color: #c53030; padding: 12px; border-radius: 8px; margin-bottom: 20px; }
        
        @media (max-width: 768px) {
            .sidebar { width: 60px; }
            .main-content { margin-left: 60px; }
            .nav-link span { display: none; }
        }
    </style>
</head>
<body>
    @include('components.sidebar')
    <main class="main-content">
        <h1>Gast bearbeiten</h1>
        
        @if($errors->any())
            <div class="error-message">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="card">
            <form action="/guests/{{ $guest->id }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text" name="name" value="{{ old('name', $guest->name) }}" required>
                </div>
                
                <div class="form-group">
                    <label>Vorname</label>
                    <input type="text" name="vorname" value="{{ old('vorname', $guest->vorname) }}">
                </div>
                
                <div class="form-group">
                    <label>E-Mail</label>
                    <input type="email" name="email" value="{{ old('email', $guest->email) }}">
                </div>
                
                <div class="form-group">
                    <label>Telefon</label>
                    <input type="text" name="phone" value="{{ old('phone', $guest->phone) }}">
                </div>
                
                <div class="form-group">
                    <label>Herkunftsort (nation1)</label>
                    <select name="nation1">
                        <option value="">-- Land wählen --</option>
                        @foreach($countryOptions as $code => $description)
                            <option value="{{ $code }}" {{ old('nation1', $guest->nation1) == $code ? 'selected' : '' }}>{{ $description }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Aufenthaltsort (nation2)</label>
                    <select name="nation2">
                        <option value="">-- Land wählen --</option>
                        @foreach($countryOptions as $code => $description)
                            <option value="{{ $code }}" {{ old('nation2', $guest->nation2) == $code ? 'selected' : '' }}>{{ $description }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Adresse</label>
                    <textarea name="address" rows="2">{{ old('address', $guest->address) }}</textarea>
                </div>
                
                <div class="form-group">
                    <label>Stadt</label>
                    <input type="text" name="city" value="{{ old('city', $guest->city) }}">
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Speichern</button>
                    <a href="/guests" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Zurück</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>

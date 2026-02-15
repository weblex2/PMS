<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Einstellungen - PMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: linear-gradient(180deg, #1e3a5f 0%, #0d2137 100%); color: white; padding: 20px 0; position: fixed; height: 100vh; overflow-y: auto; }
        .nav { padding: 0 10px; }
        .nav-link { display: flex; align-items: center; padding: 12px 15px; color: rgba(255,255,255,0.8); text-decoration: none; border-radius: 8px; margin-bottom: 5px; transition: all 0.3s; }
        .nav-link:hover { background: rgba(255,255,255,0.1); color: white; }
        .nav-link.active { background: rgba(255,255,255,0.2); color: white; }
        .nav-link i { width: 25px; font-size: 18px; }
        .main-content { margin-left: 250px; flex: 1; padding: 30px; }
        .card { background: white; border-radius: 12px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 25px; color: #1e3a5f; }
        .btn { padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .btn-primary { background: #1e3a5f; color: white; }
        .btn-primary:hover { background: #2c5282; }
        .btn-danger { background: #e53e3e; color: white; }
        .btn-danger:hover { background: #c53030; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f7fafc; font-weight: 600; color: #4a5568; }
        .form-row { display: grid; grid-template-columns: 1fr 2fr auto; gap: 10px; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; }
        @media (max-width: 768px) { .sidebar { width: 60px; } .main-content { margin-left: 60px; } .nav-link span { display: none; } }
    </style>
</head>
<body>
    @include('components.sidebar')
    <main class="main-content">
        <h1>Einstellungen</h1>
        @if(session('success'))<div class="card" style="background: #c6f6d5; color: #276749;">{{ session('success') }}</div>@endif
        <div class="card">
            <form action="/settings" method="POST" class="form-row">
                @csrf
                <div class="form-group"><input type="text" name="key" placeholder="Key" required></div>
                <div class="form-group"><input type="text" name="value" placeholder="Value"></div>
                <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i></button>
            </form>
        </div>
        <div class="card">
            @if($settings->count() > 0)
            <table>
                <thead><tr><th>Key</th><th>Value</th><th style="width: 80px;">Aktion</th></tr></thead>
                <tbody>
                    @foreach($settings as $setting)
                    <tr>
                        <td><code>{{ $setting->setting_key }}</code></td>
                        <td>{{ $setting->value }}</td>
                        <td>
                            <form action="/settings/{{ $setting->setting_key }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Löschen?');"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else<p style="color: #718096;">Keine Einstellungen vorhanden.</p>@endif
        </div>
    </main>
</body>
</html>

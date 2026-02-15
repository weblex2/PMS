<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zimmerarten - PMS</title>
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
        
        .header-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .btn-primary { background: #1e3a5f; color: white; }
        .btn-primary:hover { background: #2c5282; }
        .btn-secondary { background: #718096; color: white; }
        .btn-secondary:hover { background: #4a5568; }
        .btn-danger { background: #e53e3e; color: white; }
        .btn-danger:hover { background: #c53030; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f7fafc; font-weight: 600; color: #4a5568; }
        tr:hover { background: #f7fafc; }
        
        .action-btns { display: flex; gap: 8px; }
        .empty-state { text-align: center; padding: 60px 20px; color: #718096; }
        .empty-state i { font-size: 48px; margin-bottom: 15px; opacity: 0.5; }
        
        @media (max-width: 768px) {
            .sidebar { width: 60px; }
            .main-content { margin-left: 60px; }
            .nav-link span { display: none; }
            .nav-link i { width: auto; }
        }
    </style>
</head>
<body>
    @include('components.sidebar')
    <main class="main-content">
        <div class="header-row">
            <h1>Zimmerarten</h1>
            <a href="/room-types/create" class="btn btn-primary"><i class="fa fa-plus"></i> Neue Zimmerart</a>
        </div>
        
        @if(session('success'))
            <div class="card" style="background: #c6f6d5; color: #276749; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="card">
            @if($roomTypes->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Betten</th>
                        <th>Beschreibung</th>
                        <th style="width: 120px;">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roomTypes as $type)
                    <tr>
                        <td><strong>{{ $type->name }}</strong></td>
                        <td>{{ $type->bed_count }}</td>
                        <td>{{ $type->description ?? '-' }}</td>
                        <td class="action-btns">
                            <a href="/room-types/{{ $type->id }}/edit" class="btn btn-secondary btn-sm"><i class="fa fa-pencil"></i></a>
                            <form action="/room-types/{{ $type->id }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Zimmerart wirklich löschen?');"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <div class="empty-state">
                    <i class="fa fa-bed"></i>
                    <h3>Keine Zimmerarten vorhanden</h3>
                    <p>Erstellen Sie Ihre erste Zimmerart.</p>
                </div>
            @endif
        </div>
    </main>
</body>
</html>

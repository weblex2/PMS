<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kundenverwaltung - PMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; display: flex; min-height: 100vh; }
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
        .alert-success { background: #c6f6d5; color: #276749; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .alert-error { background: #fed7d7; color: #c53030; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        @media (max-width: 768px) { .main-content { margin-left: 60px; } table { font-size: 12px; } th, td { padding: 8px; } }
</style>
</head>
<body>
    @include('components.sidebar')
    <main class="main-content">
        <div class="header-row">
            <h1>Kundenverwaltung</h1>
            <a href="/kunden/create" class="btn btn-primary"><i class="fa fa-plus"></i> Neuer Kunde</a>
        </div>

        @if(session('success'))
            <div class="alert-success">{ session('success') }</div>
        @endif
        @if(session('error'))
            <div class="alert-error">{ session('error') }</div>
        @endif

        <div class="card">
            @if($kunden->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Vorname</th>
                        <th>Firma</th>
                        <th>E-Mail</th>
                        <th>Telefon</th>
                        <th>Stadt</th>
                        <th style="width: 120px;">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kunden as $kunde)
                    <tr>
                        <td><strong>{ $kunde->name }</strong></td>
                        <td>{ $kunde->vorname ?? '-' }</td>
                        <td>{ $kunde->firma ?? '-' }</td>
                        <td>{ $kunde->email ?? '-' }</td>
                        <td>{ $kunde->phone ?? '-' }</td>
                        <td>{ $kunde->city ?? '-' }</td>
                        <td class="action-btns">
                            <a href="/kunden/{ $kunde->id }/edit" class="btn btn-secondary btn-sm"><i class="fa fa-pencil"></i></a>
                            <form action="/kunden/{ $kunde->id }" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Kunde wirklich löschen?');"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <div class="empty-state">
                    <i class="fa fa-address-book"></i>
                    <h3>Keine Kunden vorhanden</h3>
                    <p>Erstellen Sie Ihren ersten Kunden.</p>
                </div>
            @endif
        </div>
    </main>
</body>
</html>
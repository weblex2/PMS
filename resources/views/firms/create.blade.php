<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neues Haus - PMS</title>
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
        .btn-secondary { background: #718096; color: white; }
        .btn-secondary:hover { background: #4a5568; }
        
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; color: #4a5568; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: border-color 0.3s; }
        .form-group input:focus { outline: none; border-color: #1e3a5f; }
        .form-group small { color: #718096; margin-top: 5px; display: block; }
        
        .error-message { background: #fed7d7; color: #c53030; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .error-message ul { margin: 0; padding-left: 20px; }
        
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
        <h1>Neues Haus</h1>
        
        @if($errors->any())
            <div class="error-message">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="card">
            <form action="/firms" method="POST">
                @csrf
                
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text" name="name" placeholder="z.B. Haus Berlin" required>
                </div>
                
                <div class="form-group">
                    <label>Datenbank-Name *</label>
                    <input type="text" name="code" placeholder="z.B. haus_berlin" required>
                    <small>Eindeutiger Name für die Datenbank dieses Hauses</small>
                </div>
                
                <div style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Haus erstellen</button>
                    <a href="/firms" class="btn btn-secondary"><i class="fa fa-times"></i> Abbrechen</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>

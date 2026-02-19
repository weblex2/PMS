<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - aiPms</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f5f5; display: flex; min-height: 100vh; }
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
        .card h3 { color: #1e3a5f; margin-bottom: 15px; font-size: 18px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: linear-gradient(135deg, #1e3a5f 0%, #0d2137 100%); color: white; border-radius: 12px; padding: 25px; text-align: center; }
        .stat-card i { font-size: 36px; margin-bottom: 10px; opacity: 0.8; }
        .stat-card .number { font-size: 32px; font-weight: bold; }
        .stat-card .label { font-size: 14px; opacity: 0.8; margin-top: 5px; }
    </style>
</head>
<body>
    <x-sidebar />
    <main class="main-content">
        <h1 style="margin-bottom: 25px; color: #1e3a5f;">Dashboard</h1>
        
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fa fa-users"></i>
                <div class="number">156</div>
                <div class="label">Gäste heute</div>
            </div>
            <div class="stat-card">
                <i class="fa fa-bed"></i>
                <div class="number">42</div>
                <div class="label">Belegte Zimmer</div>
            </div>
            <div class="stat-card">
                <i class="fa fa-calendar-check"></i>
                <div class="number">18</div>
                <div class="label">Check-ins heute</div>
            </div>
            <div class="stat-card">
                <i class="fa fa-euro-sign"></i>
                <div class="number">€12.450</div>
                <div class="label">Umsatz heute</div>
            </div>
        </div>
        
        <div class="card">
            <h3>Willkommen im aiPms Dashboard</h3>
            <p>Wählen Sie einen Menüpunkt aus der Navigation, um zu beginnen.</p>
        </div>
    </main>
</body>
</html>

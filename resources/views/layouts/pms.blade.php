<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/reservations.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, sans-serif; background: #f5f5f5; display: flex; min-height: 100vh; }
        
        .sidebar { 
            width: 250px; 
            background: linear-gradient(180deg, #1e3a5f 0%, #0d2137 100%); 
            color: white; 
            padding: 20px 0; 
            position: fixed; 
            height: 100vh; 
            overflow-y: auto; 
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }
        .sidebar .nav-header { 
            text-align: center; 
            padding: 20px 10px; 
            border-bottom: 1px solid rgba(255,255,255,0.1); 
            margin-bottom: 20px; 
        }
        .sidebar .nav-header h2 { 
            color: #ffffff; 
            margin: 0; 
            font-size: 28px; 
            font-weight: 700; 
            letter-spacing: 1px; 
        }
        .nav { padding: 0 10px; flex: 1; }
        .nav-link { 
            display: flex; 
            align-items: center; 
            padding: 12px 15px; 
            color: rgba(255,255,255,0.8); 
            text-decoration: none; 
            border-radius: 8px; 
            margin-bottom: 5px; 
            transition: all 0.3s; 
        }
        .nav-link:hover { background: rgba(255,255,255,0.1); color: white; }
        .nav-link.active { background: rgba(255,255,255,0.2); color: white; }
        .nav-link i { width: 25px; font-size: 18px; }
        
        .nav-dropdown { margin-bottom: 5px; }
        .nav-dropdown-header {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: rgba(255,255,255,0.8);
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .nav-dropdown-header:hover { background: rgba(255,255,255,0.1); color: white; }
        .nav-dropdown-header i:first-child { width: 25px; font-size: 18px; }
        .nav-dropdown-content { display: none; padding-left: 15px; }
        .nav-dropdown.open .nav-dropdown-content { display: block; }
        .nav-dropdown.open > .nav-dropdown-header { background: rgba(255,255,255,0.1); color: white; }
        
        .main-content { margin-left: 250px; flex: 1; padding: 30px; }
        
        .card { background: white; border-radius: 12px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .card h1, .card h2, .card h3 { color: #1e3a5f; margin-bottom: 15px; }
        .card h1 { font-size: 24px; }
        
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: #1e3a5f; border-radius: 8px; color: white; border: none; cursor: pointer; text-decoration: none; font-size: 14px; }
        .btn:hover { background: #0d2137; color: white; }
        .btn-sm { padding: 6px 10px; font-size: 12px; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; font-weight: 600; color: #475569; }
        
        .badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-confirmed { background: #d4edda; color: #155724; }
        .badge-checked_in { background: #cce5ff; color: #004085; }
        .badge-checked_out { background: #d1ecf1; color: #0c5460; }
        .badge-cancelled { background: #f8d7da; color: #721c24; }
        
        .logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: rgba(255,100,100,0.8);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .logout-btn:hover { background: rgba(255,255,255,0.1); color: #ff6b6b; }
        .logout-btn i { width: 25px; font-size: 18px; }
        
        @media (max-width: 1024px) {
            .sidebar { width: 200px; }
            .main-content { margin-left: 200px; padding: 20px; }
        }
        
        @media (max-width: 768px) {
            .sidebar { width: 280px; transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 70px 15px 20px 15px; }
        }
    </style>
</head>
<body>
    <x-sidebar />
    
    <main class="main-content">
        @yield("content")
    </main>
</body>
</html>

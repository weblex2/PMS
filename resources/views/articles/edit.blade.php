<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel bearbeiten - aiPms</title>
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
        .nav-link i { width: 25px; font-size: 18px; }
        .main-content { margin-left: 250px; flex: 1; padding: 30px; }
        .card { background: white; border-radius: 12px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 25px; color: #1e3a5f; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: linear-gradient(135deg, #1e3a5f 0%, #0d2137 100%); border-radius: 8px; color: #fff; text-decoration: none; border: none; cursor: pointer; font-size: 14px; }
        .btn-secondary { background: #6c757d; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <x-sidebar />
    <main class="main-content">
        <h1>Artikel bearbeiten: {{ $article->name }}</h1>
        
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
            <form action="/articles/{{ $article->id }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid-2">
                    <div class="form-group">
                        <label>Artikelnummer *</label>
                        <input type="text" name="article_number" value="{{ old('article_number', $article->article_number) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Name *</label>
                        <input type="text" name="name" value="{{ old('name', $article->name) }}" required>
                    </div>
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Beschreibung</label>
                        <textarea name="description" rows="3">{{ old('description', $article->description) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Einkaufspreis (EK) *</label>
                        <input type="number" name="purchase_price" step="0.01" value="{{ old('purchase_price', $article->purchase_price) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Verkaufspreis (VK) *</label>
                        <input type="number" name="selling_price" step="0.01" value="{{ old('selling_price', $article->selling_price) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Lagerbestand</label>
                        <input type="number" name="stock" value="{{ old('stock', $article->stock) }}">
                    </div>
                    <div class="form-group">
                        <label>Kategorie</label>
                        <input type="text" name="category" value="{{ old('category', $article->category) }}">
                    </div>
                </div>
                <div style="margin-top: 20px;">
                    <button type="submit" class="btn">Aktualisieren</button>
                    <a href="/articles" class="btn btn-secondary">Abbrechen</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>

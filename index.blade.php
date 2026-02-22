<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank-Umsätze</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f5f5; padding: 20px; }
        .container { max-width: 1400px; margin: 0 auto; }
        h1 { color: #333; margin: 0 0 20px 0; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .btn { display: inline-block; padding: 10px 20px; background: #0066cc; color: white; text-decoration: none; border-radius: 6px; border: none; cursor: pointer; }
        .btn:hover { background: #0052a3; }
        .card { background: white; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .stat-item { text-align: center; padding: 15px; background: #f8f9fa; border-radius: 8px; }
        .stat-label { font-size: 12px; color: #666; text-transform: uppercase; }
        .stat-value { font-size: 24px; font-weight: bold; margin-top: 5px; }
        .stat-value.income { color: #28a745; }
        .stat-value.expense { color: #dc3545; }
        .filter-bar { display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end; }
        .filter-group { display: flex; flex-direction: column; }
        .filter-group label { font-size: 12px; color: #666; margin-bottom: 5px; }
        .filter-group input { padding: 8px 12px; border: 1px solid #ddd; border-radius: 4px; }
        .table-container { overflow-x: auto; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; font-weight: 600; }
        .amount { font-weight: bold; }
        .amount.positive { color: #28a745; }
        .amount.negative { color: #dc3545; }
        .category-badge { display: inline-block; padding: 4px 8px; background: #e3f2fd; color: #1565c0; border-radius: 4px; font-size: 12px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 6px; margin-bottom: 20px; }
        
        /* Custom dropdown */
        .dropdown-checkbox { position: relative; display: inline-block; }
        .dropdown-checkbox .dropdown-menu { 
            display: none; 
            position: absolute; 
            top: 100%; 
            left: 0; 
            z-index: 1000; 
            min-width: 200px; 
            max-height: 300px;
            overflow-y: auto;
            background: white; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 10px;
        }
        .dropdown-checkbox.show .dropdown-menu { display: block; }
        .dropdown-checkbox .dropdown-item { 
            display: flex; 
            align-items: center; 
            padding: 8px; 
            cursor: pointer; 
        }
        .dropdown-checkbox .dropdown-item:hover { background: #f8f9fa; }
        .dropdown-checkbox .dropdown-item input { margin-right: 8px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💳 Bank-Umsätze</h1>
            <a href="{{ route('banking.upload') }}" class="btn">📥 CSV importieren</a>
        </div>
        
        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif
        
        <!-- Stats -->
        <div class="card">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-label">Anzahl Buchungen</div>
                    <div class="stat-value">{{ number_format($stats['count']) }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Gesamteinnahmen</div>
                    <div class="stat-value income">{{ number_format($stats['total_income'] ?? 0, 2, ',', '.') }} €</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Gesamtausgaben</div>
                    <div class="stat-value expense">{{ number_format($stats['total_expense'] ?? 0, 2, ',', '.') }} €</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Saldo</div>
                    <div class="stat-value {{ ($stats['total_income'] ?? 0) + ($stats['total_expense'] ?? 0) >= 0 ? 'income' : 'expense' }}">
                        {{ number_format(($stats['total_income'] ?? 0) + ($stats['total_expense'] ?? 0), 2, ',', '.') }} €
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="card">
            <form method="GET" action="{{ route('banking.index') }}" class="filter-bar">
                <div class="filter-group">
                    <label>Suchen</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Verwendungszweck, Name..." style="width: 200px;">
                </div>
                <div class="filter-group">
                    <label>Kategorien</label>
                    <div class="dropdown-checkbox" id="categoryDropdown">
                        <button type="button" class="btn" onclick="toggleDropdown()">Kategorien ▼</button>
                        <div class="dropdown-menu">
                            @php $selectedCats = request('kategorie', []) @endphp
                            @foreach($categories as $cat)
                            <label class="dropdown-item">
                                <input type="checkbox" name="kategorie[]" value="{{ $cat }}" {{ in_array($cat, $selectedCats) ? 'checked' : '' }}>
                                {{ $cat }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="filter-group">
                    <label>Von</label>
                    <input type="date" name="von" value="{{ request('von') }}">
                </div>
                <div class="filter-group">
                    <label>Bis</label>
                    <input type="date" name="bis" value="{{ request('bis') }}">
                </div>
                <div class="filter-group">
                    <button type="submit" class="btn">Filtern</button>
                </div>
                <div class="filter-group">
                    <a href="{{ route('banking.index') }}" class="btn" style="background: #666;">Zurücksetzen</a>
                </div>
            </form>
        </div>
        
        <!-- Filter Summe -->
        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <strong>Gefilterte Summe:</strong>
                @php
                    $filterSum = ($stats['total_income'] ?? 0) + ($stats['total_expense'] ?? 0);
                    $filterColor = $filterSum >= 0 ? '#28a745' : '#dc3545';
                @endphp
                <div style="font-size: 20px; font-weight: bold; color: {{ $filterColor }}">
                    {{ number_format($filterSum, 2, ',', '.') }} €
                </div>
            </div>
        </div>
        
        <!-- Table -->
        <div class="card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Datum</th>
                            <th>User</th>
                            <th>Text</th>
                            <th>Empfänger</th>
                            <th>Verwendungszweck</th>
                            <th>Kategorie</th>
                            <th>Betrag</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $t)
                        <tr>
                            <td>
                                {{ $t->buchungstag?->format('d.m.Y') }}
                                @if($t->valutadatum && $t->valutadatum != $t->buchungstag)
                                    <div style="color: #999; font-size: 12px;">Valuta: {{ $t->valutadatum->format('d.m.Y') }}</div>
                                @endif
                            </td>
                            <td>{{ $t->user_name ?? $t->user }}</td>
                            <td>{{ $t->buchungstext }}</td>
                            <td>{{ $t->beguenstigter }}</td>
                            <td>{{ $t->verwendungszweck }}</td>
                            <td>
                                @if($t->kategorie)
                                    <span class="category-badge">{{ $t->kategorie }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="amount {{ $t->betrag >= 0 ? 'positive' : 'negative' }}">
                                    {{ number_format($t->betrag, 2, ',', '.') }} €
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-4">
                {{ $transactions->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    
    <script>
        function toggleDropdown() {
            document.getElementById('categoryDropdown').classList.toggle('show');
        }
        
        document.addEventListener('click', function(e) {
            var dropdown = document.getElementById('categoryDropdown');
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    </script>
</body>
</html>

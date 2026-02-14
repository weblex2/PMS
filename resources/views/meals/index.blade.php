<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Mahlzeiten - PMS</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:"Segoe UI",system-ui,sans-serif;background:linear-gradient(135deg,#0f0f23 0%,#1a1a3e 50%,#0d0d2b 100%);min-height:100vh;color:#fff}
.container{max-width:1200px;margin:0 auto;padding:30px 20px}
h1{font-size:2.5rem;font-weight:700;margin-bottom:20px;background:linear-gradient(135deg,#667eea,#f093fb);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.nav{display:flex;gap:10px;margin-bottom:30px}
.nav a{color:#fff;text-decoration:none;padding:10px 20px;background:rgba(255,255,255,0.1);border-radius:8px}
.card{background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:20px;margin-bottom:20px}
table{width:100%;border-collapse:collapse;background:rgba(255,255,255,0.05);border-radius:12px}
th,td{padding:12px 15px;text-align:left;border-bottom:1px solid rgba(255,255,255,0.1)}
th{background:rgba(255,255,255,0.1);color:#a0a0b0}
.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:8px;color:#fff;border:none;cursor:pointer}
.btn-danger{background:linear-gradient(135deg,#fa709a,#f43f5e)}
input,select{width:100%;padding:10px 15px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);border-radius:8px;color:#fff}
.form-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:15px;margin-bottom:15px}
</style>
</head>
<body>
<div class=container>
<h1>🍽️ Mahlzeiten</h1>
<div class=nav>
<a href="/"><i class="fas fa-home"></i> Dashboard</a>
<a href="/articles"><i class="fas fa-box"></i> Artikel</a>
<a href="/meals" class=active><i class="fas fa-utensils"></i> Mahlzeiten</a>
</div>

<div class=card>
<h3>Neue Mahlzeit</h3>
<form method="POST" action="/meals">
@csrf
<div class=form-row>
<input type="text" name="name" placeholder="Name (z.B. Frühstück)" required>
<input type="number" name="price" step="0.01" placeholder="Preis (€)" required>
</div>
<input type="text" name="description" placeholder="Beschreibung">
<button type="submit" class=btn><i class="fas fa-save"></i> Speichern</button>
</form>
</div>

<div class=card>
<table>
<tr><th>Name</th><th>Preis</th><th>Beschreibung</th><th>Aktionen</th></tr>
@forelse(\ ?? \[] as \)
<tr>
<td>{{ \->name }}</td>
<td>{{ number_format(\->price, 2, \,\, \.\) }} €</td>
<td>{{ \->description ?: - }}</td>
<td><a href="/meals/{{ \->id }}/delete" class="btn btn-danger btn-sm" onclick="return confirm(Löschen?)"><i class="fas fa-trash"></i></a></td>
</tr>
@empty
<tr><td colspan=4 style="text-align:center;color:#a0a0b0">Keine Mahlzeiten</td></tr>
@endforelse
</table>
</div>
</div>
</body>
</html>

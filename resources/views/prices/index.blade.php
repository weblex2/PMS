<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Preislisten - PMS</title>
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
input,select{width:100%;padding:10px 15px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);border-radius:8px;color:#fff}
.form-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:15px;margin-bottom:15px}
</style>
</head>
<body>
<div class=container>
<h1>💵 Preislisten</h1>
<div class=nav>
<a href="/"><i class="fas fa-home"></i> Dashboard</a>
<a href="/articles"><i class="fas fa-box"></i> Artikel</a>
<a href="/prices" class=active><i class="fas fa-tags"></i> Preislisten</a>
</div>

<div class=card>
<h3>Neue Preisliste</h3>
<form method="POST" action="/prices">
@csrf
<div class=form-row>
<input type="text" name="name" placeholder="Name" required>
<input type="text" name="code" placeholder="Code" required>
<input type="date" name="valid_from" required>
<input type="date" name="valid_until" required>
</div>
<button type="submit" class=btn><i class="fas fa-save"></i> Erstellen</button>
</form>
</div>

<div class=card>
<table>
<tr><th>Name</th><th>Code</th><th>Gültig von</th><th>Bis</th></tr>
@forelse(\ ?? \[] as \)
<tr>
<td>{{ \->name }}</td>
<td>{{ \->code }}</td>
<td>{{ \->valid_from }}</td>
<td>{{ \->valid_until }}</td>
</tr>
@empty
<tr><td colspan=4 style="text-align:center;color:#a0a0b0">Keine Preislisten</td></tr>
@endforelse
</table>
</div>
</div>
</body>
</html>

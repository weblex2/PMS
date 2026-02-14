<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Journal - PMS</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:"Segoe UI",system-ui,sans-serif;background:linear-gradient(135deg,#0f0f23 0%,#1a1a3e 50%,#0d0d2b 100%);min-height:100vh;color:#fff}
.container{max-width:1600px;margin:0 auto;padding:30px 20px}
h1{font-size:2.5rem;font-weight:700;margin-bottom:20px;background:linear-gradient(135deg,#667eea,#f093fb);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.nav{display:flex;gap:10px;margin-bottom:30px;flex-wrap:wrap}
.nav a{color:#fff;text-decoration:none;padding:10px 20px;background:rgba(255,255,255,0.1);border-radius:8px}
.card{background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:20px;margin-bottom:20px}
table{width:100%;border-collapse:collapse;background:rgba(255,255,255,0.05);border-radius:12px}
th,td{padding:12px 15px;text-align:left;border-bottom:1px solid rgba(255,255,255,0.1)}
th{background:rgba(255,255,255,0.1);color:#a0a0b0}
.direction-in{color:#43e97b}
.direction-out{color:#fa709a}
.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:linear-gradient(135deg,#667eea,#764ba2);border-radius:8px;color:#fff;border:none;cursor:pointer}
input,select{width:100%;padding:10px 15px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);border-radius:8px;color:#fff}
label{display:block;margin-bottom:5px;color:#a0a0b0}
.form-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:15px;margin-bottom:15px}
</style>
</head>
<body>
<div class=container>
<h1>💰 Journal</h1>
<div class=nav>
<a href="/"><i class="fas fa-home"></i> Dashboard</a>
<a href="/reservations"><i class="fas fa-calendar-check"></i> Reservierungen</a>
<a href="/journal" class=active><i class="fas fa-euro-sign"></i> Journal</a>
<a href="/invoices"><i class="fas fa-file-invoice"></i> Rechnungen</a>
</div>

<div class=card>
<h3>Neue Buchung</h3>
<form method="POST" action="/journal">
@csrf
<div class=form-row>
<select name="type"><option value="accommodation">Übernachtung</option><option value="payment">Zahlung</option><option value="refund">Erstattung</option></select>
<select name="direction"><option value="in">Einnahme</option><option value="out">Ausgabe</option></select>
<input type="number" name="amount" step="0.01" placeholder="Betrag" required>
<select name="payment_method"><option value="">-</option><option value="cash">Bargeld</option><option value="card">Karte</option></select>
</div>
<button type="submit" class=btn><i class="fas fa-save"></i> Buchen</button>
</form>
</div>

<div class=card>
<table>
<tr><th>Nr.</th><th>Datum</th><th>Typ</th><th>Betrag</th><th>Gast</th></tr>
@forelse(\ ?? \[] as \)
<tr>
<td>{{ \->booking_number }}</td>
<td>{{ \->booking_date }}</td>
<td>{{ \->type }}</td>
<td class="{{ \->direction == in ? direction-in : direction-out }}">{{ number_format(\->amount, 2, \,\, \.\) }} €</td>
<td>{{ \->first_name }} {{ \->last_name }}</td>
</tr>
@empty
<tr><td colspan=5 style="text-align:center;color:#a0a0b0">Keine Buchungen</td></tr>
@endforelse
</table>
</div>
</div>
</body>
</html>

@extends("layouts.pms")
@section("content")

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

@endsection

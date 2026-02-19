@extends("layouts.pms")
@section("content")

<h1>Preise für Artikel: {{ $article->name }} 
    <span style="font-size: 0.8em; color: #666;">({{ $article->article_number }})</span>
</h1>

<div style="margin-bottom: 20px;">
    <a href="/articles/{{ $article->id }}/prices/create" class="btn"><i class="fa fa-plus"></i> Neuer Preis</a>
    <a href="/articles" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Zurück zu Artikeln</a>
</div>

@if(session("success"))
    <div class="alert alert-success">{{ session("success") }}</div>
@endif

<!-- Aktueller Preis -->
@php
    $currentPrice = $article->prices()->whereNull("valid_until")->orderBy("valid_from", "desc")->first();
@endphp

@if($currentPrice)
<div class="card" style="border-left: 4px solid #28a745;">
    <h3 style="color: #28a745; margin-bottom: 15px;"><i class="fa fa-check-circle"></i> Aktueller Preis</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
        <div><strong>Preis:</strong> {{ number_format($currentPrice->price, 2, ",", ".") }} EUR</div>
        <div><strong>Gültig seit:</strong> {{ $currentPrice->valid_from ? \Carbon\Carbon::parse($currentPrice->valid_from)->format("d.m.Y") : "Nicht angegeben" }}</div>
        <div><strong>Preis-Typ:</strong> {{ $currentPrice->price_type ?? "Standard" }}</div>
        @if($currentPrice->notes)
            <div style="grid-column: span 3;"><strong>Notizen:</strong> {{ $currentPrice->notes }}</div>
        @endif
    </div>
</div>
@else
<div class="card" style="border-left: 4px solid #dc3545;">
    <p style="color: #dc3545;"><i class="fa fa-exclamation-triangle"></i> Kein aktueller Preis vorhanden</p>
</div>
@endif

<!-- Preis-History -->
@php
    $priceHistory = $article->prices()
        ->where(function($query) {
            $query->whereNotNull("valid_until")
                   ->orWhere("id", "!=", $currentPrice->id ?? 0);
        })
        ->orderBy("valid_from", "desc")
        ->get();
@endphp

@if($priceHistory->count() > 0)
<div class="card">
    <h3 style="margin-bottom: 15px;"><i class="fa fa-history"></i> Preis-History</h3>
    <table>
        <thead>
            <tr>
                <th>Preis</th>
                <th>Gültig von</th>
                <th>Gültig bis</th>
                <th>Preis-Typ</th>
                <th>Notizen</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            @foreach($priceHistory as $price)
            <tr>
                <td>{{ number_format($price->price, 2, ",", ".") }} EUR</td>
                <td>{{ $price->valid_from ? \Carbon\Carbon::parse($price->valid_from)->format("d.m.Y") : "-" }}</td>
                <td>{{ $price->valid_until ? \Carbon\Carbon::parse($price->valid_until)->format("d.m.Y") : "Unbegrenzt" }}</td>
                <td>{{ $price->price_type ?? "Standard" }}</td>
                <td>{{ $price->notes ?? "-" }}</td>
                <td class="actions">
                    <form action="/prices/{{ $price->id }}" method="POST" style="display:inline;">
                        @csrf
                        @method("DELETE")
                        <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm(\"Preis wirklich löschen?\");"><i class="fa fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="card">
    <p style="color: #666;"><i class="fa fa-info-circle"></i> Keine Preis-History vorhanden.</p>
</div>
@endif

@endsection

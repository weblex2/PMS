@extends("layouts.pms")
@section("content")

<h1>Neuer Preis für: {{ $article->name }}</h1>

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
    <form action="/articles/{{ $article->id }}/prices" method="POST">
        @csrf
        <input type="hidden" name="article_id" value="{{ $article->id }}">
        
        <div class="grid-2">
            <div class="form-group">
                <label>Preis * (EUR)</label>
                <input type="number" name="price" step="0.01" value="{{ old('price') }}" required>
            </div>
            <div class="form-group">
                <label>Preis-Typ</label>
                <input type="text" name="price_type" value="{{ old('price_type', 'Standard') }}" placeholder="z.B. Standard, Einkauf, Verkauf, Rabatt">
            </div>
            <div class="form-group">
                <label>Gültig ab *</label>
                <input type="date" name="valid_from" value="{{ old('valid_from', date('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label>Gültig bis (optional)</label>
                <input type="date" name="valid_until" value="{{ old('valid_until') }}">
                <small style="color: #666;">Leer lassen für unbegrenzte Gültigkeit</small>
            </div>
            <div class="form-group" style="grid-column: span 2;">
                <label>Notizen</label>
                <textarea name="notes" rows="3" placeholder="Zusätzliche Informationen zu diesem Preis...">{{ old('notes') }}</textarea>
            </div>
        </div>
        
        <div style="margin-top: 20px;">
            <button type="submit" class="btn">Preis speichern</button>
            <a href="/articles/{{ $article->id }}/prices" class="btn btn-secondary">Abbrechen</a>
        </div>
    </form>
</div>

<!-- Info Card -->
<div class="card" style="background: #f8f9fa; border-left: 4px solid #007bff;">
    <h4 style="color: #007bff; margin-bottom: 10px;"><i class="fa fa-info-circle"></i> Hinweise</h4>
    <ul style="margin: 0; padding-left: 20px; color: #666;">
        <li>Wird kein "Gültig bis" Datum angegeben, ist der Preis dauerhaft aktiv</li>
        <li>Der neueste Preis ohne Enddatum wird als aktueller Preis angezeigt</li>
        <li>Historische Preise bleiben zur Nachverfolgung erhalten</li>
    </ul>
</div>

@endsection
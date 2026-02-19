@extends("layouts.pms")
@section("content")

<div class="header-row">
    <h1>Preise</h1>
    <a href="/prices/create" class="btn btn-primary"><i class="fa fa-plus"></i> Neuer Preis</a>
</div>

@if(session("success"))
    <div class="card" style="background: #c6f6d5; color: #276749; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
        {{ session("success") }}
    </div>
@endif

<div class="card">
    @if($prices->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Artikel</th>
                <th>Preis</th>
                <th>Typ</th>
                <th>Gültig ab</th>
                <th>Gültig bis</th>
                <th style="width: 120px;">Aktionen</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prices as $price)
            <tr>
                <td><strong>{{ $price->article->name }}</strong><br><small style="color: #718096;">{{ $price->article->article_number }}</small></td>
                <td style="font-weight: bold; color: #276749;">{{ number_format($price->price, 2, ",", ".") }} EUR</td>
                <td>
                    @if($price->price_type == "selling")
                        <span style="background: #c6f6d5; color: #276749; padding: 4px 10px; border-radius: 20px; font-size: 12px;">Verkauf</span>
                    @else
                        <span style="background: #bee3f8; color: #2b6cb0; padding: 4px 10px; border-radius: 20px; font-size: 12px;">Einkauf</span>
                    @endif
                </td>
                <td>{{ $price->valid_from->format("d.m.Y") }}</td>
                <td>{{ $price->valid_until ? $price->valid_until->format("d.m.Y") : "unbegrenzt" }}</td>
                <td class="action-btns">
                    <a href="/prices/{{ $price->id }}/edit" class="btn btn-secondary btn-sm"><i class="fa fa-pencil"></i></a>
                    <form action="/prices/{{ $price->id }}" method="POST" style="display:inline;">
                        @csrf
                        @method("DELETE")
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm("Preis wirklich löschen?");"><i class="fa fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <div class="empty-state">
            <i class="fa fa-tag"></i>
            <h3>Keine Preise vorhanden</h3>
            <p>Erstellen Sie Ihren ersten Preis.</p>
        </div>
    @endif
</div>

@endsection

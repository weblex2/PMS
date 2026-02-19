@extends("layouts.pms")
@section("content")

<div class="header-row">
    <h1>Gästeverwaltung</h1>
    <a href="/guests/create" class="btn btn-primary"><i class="fa fa-plus"></i> Neuer Gast</a>
</div>

@if(session("success"))
    <div class="card" style="background: #c6f6d5; color: #276749; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
        {{ session("success") }}
    </div>
@endif

<style>
    table { width: 100%; border-collapse: collapse; font-size: 14px; }
    table th, table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
    table th { background: #f8f9fa; font-weight: 600; }
    .badge { font-size: 14px; padding: 4px 10px; }
</style>

<div class="card">
    @if($guests->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Vorname</th>
                <th>Match1</th>
                <th>Match2</th>
                <th>Herkunft</th>
                <th>Aufenthalt</th>
                <th>E-Mail</th>
                <th>Telefon</th>
                <th style="width: 120px;">Aktionen</th>
            </tr>
        </thead>
        <tbody>
@foreach($guests as $guest)
            <tr>
                <td><strong>{{ $guest->name }}</strong></td>
                <td>{{ $guest->vorname ?? "-" }}</td>
                <td><span class="badge badge-info">{{ $guest->match1 ?? "-" }}</span></td>
                <td>{{ $guest->match ?? "-" }}</td>
                <td>
                    @if($guest->nation1)
                        <span class="badge badge-success">{{ $guest->nation1 }}</span>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($guest->nation2)
                        <span class="badge badge-warning">{{ $guest->nation2 }}</span>
                    @else
                        -
                    @endif
                </td>
                <td>{{ $guest->email ?? "-" }}</td>
                <td>{{ $guest->phone ?? "-" }}</td>
                <td class="action-btns">
                    <a href="/guests/{{ $guest->id }}/edit" class="btn btn-secondary btn-sm"><i class="fa fa-pencil"></i></a>
                    <form action="/guests/{{ $guest->id }}" method="POST" style="display:inline;">
                        @csrf
                        @method("DELETE")
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\"Gast wirklich löschen?\");"><i class="fa fa-trash"></i></button>
                    </form>
                </td>
            </tr>
@endforeach
        </tbody>
    </table>
    @else
        <div class="empty-state">
            <i class="fa fa-users"></i>
            <h3>Keine Gäste vorhanden</h3>
            <p>Erstellen Sie Ihren ersten Gast.</p>
        </div>
    @endif
</div>

@endsection

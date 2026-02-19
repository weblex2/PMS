@extends("layouts.pms")
@section("content")

<div class="header-row">
    <h1><i class="fa fa-bed"></i> Zimmerverwaltung</h1>
    <a href="{{ route("rooms.create") }}" class="btn btn-primary">
        <i class="fa fa-plus"></i> Neues Zimmer
    </a>
</div>

@if(session("success"))
    <div style="background: #c6f6d5; color: #276749; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <i class="fa fa-check-circle"></i> {{ session("success") }}
    </div>
@endif

<div class="card">
    @if($rooms->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Zimmernr.</th>
                    <th>Typ</th>
                    <th>Betten</th>
                    <th>Gültig von</th>
                    <th>Gültig bis</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rooms as $room)
                    <tr>
                        <td><strong>{{ $room->room_number }}</strong></td>
                        <td>{{ $room->type ?? "-" }}</td>
                        <td>{{ $room->bed_count ?? "-" }}</td>
                        <td>{{ $room->valid_from ? $room->valid_from->format("d.m.Y") : "-" }}</td>
                        <td>{{ $room->valid_until ? $room->valid_until->format("d.m.Y") : "-" }}</td>
                        <td>
                            <div class="action-btns">
                                <a href="{{ route("rooms.edit", $room->id) }}" class="btn btn-secondary btn-sm">
                                    <i class="fa fa-edit"></i> Bearbeiten
                                </a>
                                <form action="{{ route("rooms.destroy", $room->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method("DELETE")
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm("Möchten Sie dieses Zimmer wirklich löschen?")">
                                        <i class="fa fa-trash"></i> Löschen
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <i class="fa fa-bed"></i>
            <h3>Keine Zimmer vorhanden</h3>
            <p>Erstellen Sie Ihr erstes Zimmer, um zu beginnen.</p>
            <a href="{{ route("rooms.create") }}" class="btn btn-primary" style="margin-top: 20px;">
                <i class="fa fa-plus"></i> Zimmer erstellen
            </a>
        </div>
    @endif
</div>

@endsection

@extends("layouts.pms")
@section("content")

<h1>Artikel <a href="/articles/create" class="btn"><i class="fa fa-plus"></i> Hinzufuegen</a></h1>

@if(session("success"))
    <div class="alert alert-success">{{ session("success") }}</div>
@endif

<div class="card">
    @if($articles->count() > 0)
    <table>
        <thead>
            <tr><th>Nr.</th><th>Artikelnummer</th><th>Name</th><th>Kategorie</th><th>Aktionen</th></tr>
        </thead>
        <tbody>
            @foreach($articles as $article)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $article->article_number }}</td>
                <td>{{ $article->name }}</td>
                <td>{{ $article->category }}</td>
                <td class="actions">
                    <a href="/articles/{{ $article->id }}/edit" class="btn btn-sm btn-edit"><i class="fa fa-pencil"></i></a>
                    <form action="/articles/{{ $article->id }}" method="POST" style="display:inline;">
                        @csrf
                        @method("DELETE")
                        <button type="submit" class="btn btn-sm btn-delete" onclick="return confirm(\"Artikel wirklich loeschen?\");"><i class="fa fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p style="color: #666;">Keine Artikel vorhanden.</p>
    @endif
</div>

@endsection

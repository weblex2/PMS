<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::all();
        return view("articles.index", compact("articles"));
    }

    public function create()
    {
        return view("articles.create");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "article_number" => "required|unique:articles",
            "name" => "required",
            "description" => "nullable",
            "category" => "nullable",
        ]);

        Article::create($validated);
        return redirect()->route("articles.index")->with("success", "Artikel erfolgreich erstellt.");
    }

    public function show(Article $article)
    {
        return view("articles.show", compact("article"));
    }

    public function edit(Article $article)
    {
        return view("articles.edit", compact("article"));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            "article_number" => "required|unique:articles,article_number," . $article->id,
            "name" => "required",
            "description" => "nullable",
            "category" => "nullable",
        ]);

        $article->update($validated);
        return redirect()->route("articles.index")->with("success", "Artikel erfolgreich aktualisiert.");
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route("articles.index")->with("success", "Artikel erfolgreich gelöscht.");
    }
    
    public function prices($id)
    {
        $article = Article::with("prices")->findOrFail($id);
        return view("articles.prices.index", compact("article"));
    }
}

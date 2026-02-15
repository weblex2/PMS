<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Models\Article;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index()
    {
        $prices = Price::with('article')->orderBy('valid_from', 'desc')->get();
        return view('prices.index', compact('prices'));
    }

    public function create()
    {
        $articles = Article::all();
        return view('prices.create', compact('articles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'article_id' => 'required|exists:articles,id',
            'price' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'price_type' => 'required|in:selling,purchase',
            'notes' => 'nullable|string',
        ]);

        Price::create($validated);
        return redirect()->route('prices.index')->with('success', 'Preis erfolgreich angelegt.');
    }

    public function edit(Price $price)
    {
        $articles = Article::all();
        return view('prices.edit', compact('price', 'articles'));
    }

    public function update(Request $request, Price $price)
    {
        $validated = $request->validate([
            'article_id' => 'required|exists:articles,id',
            'price' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'price_type' => 'required|in:selling,purchase',
            'notes' => 'nullable|string',
        ]);

        $price->update($validated);
        return redirect()->route('prices.index')->with('success', 'Preis erfolgreich aktualisiert.');
    }

    public function destroy(Price $price)
    {
        $price->delete();
        return redirect()->route('prices.index')->with('success', 'Preis erfolgreich gelöscht.');
    }
}

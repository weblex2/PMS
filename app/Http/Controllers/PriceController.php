<?php

namespace App\Http\Controllers;

use App\Models\Price;
use App\Models\Article;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index()
    {
        $prices = Price::with("article")->orderBy("valid_from", "desc")->get();
        return view("prices.index", compact("prices"));
    }

    public function create()
    {
        $articles = Article::all();
        return view("prices.create", compact("articles"));
    }
    
    public function createForArticle($articleId)
    {
        $article = Article::findOrFail($articleId);
        return view("articles.prices.create", compact("article"));
    }
    
    public function storeForArticle(Request $request, $articleId)
    {
        $article = Article::findOrFail($articleId);
        
        $validated = $request->validate([
            "price" => "required|numeric|min:0",
            "valid_from" => "required|date",
            "valid_until" => "nullable|date|after:valid_from",
            "price_type" => "nullable|string",
            "notes" => "nullable|string",
        ]);
        
        $validated["article_id"] = $articleId;

        Price::create($validated);
        return redirect()->route("articles.prices", $articleId)->with("success", "Preis erfolgreich angelegt.");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "article_id" => "required|exists:articles,id",
            "price" => "required|numeric|min:0",
            "valid_from" => "required|date",
            "valid_until" => "nullable|date|after:valid_from",
            "price_type" => "required|in:selling,purchase",
            "notes" => "nullable|string",
        ]);

        Price::create($validated);
        return redirect()->route("prices.index")->with("success", "Preis erfolgreich angelegt.");
    }

    public function edit(Price $price)
    {
        $articles = Article::all();
        return view("prices.edit", compact("price", "articles"));
    }

    public function update(Request $request, Price $price)
    {
        $validated = $request->validate([
            "article_id" => "required|exists:articles,id",
            "price" => "required|numeric|min:0",
            "valid_from" => "required|date",
            "valid_until" => "nullable|date|after:valid_from",
            "price_type" => "required|in:selling,purchase",
            "notes" => "nullable|string",
        ]);

        $price->update($validated);
        return redirect()->route("prices.index")->with("success", "Preis erfolgreich aktualisiert.");
    }

    public function destroy(Price $price)
    {
        $price->delete();
        
        if (request()->header("referer") && str_contains(request()->header("referer"), "/prices")) {
            return back()->with("success", "Preis erfolgreich gelöscht.");
        }
        
        return redirect()->route("prices.index")->with("success", "Preis erfolgreich gelöscht.");
    }
    
    public function bulkUpdateForArticle(Request $request, $articleId)
    {
        $article = Article::findOrFail($articleId);
        
        // Single price update (from inline edit)
        if ($request->has('single_update')) {
            $request->validate([
                'price_id' => 'required|exists:prices,id',
                'price' => 'required|numeric|min:0',
                'valid_from' => 'required|date',
                'valid_until' => 'nullable|date',
                'price_type' => 'nullable|in:selling,purchase,standard',
            ]);
            
            $price = Price::find($request->price_id);
            if ($price) {
                $price->update([
                    'price' => $request->price,
                    'valid_from' => $request->valid_from,
                    'valid_until' => $request->valid_until ?: null,
                    'price_type' => $request->price_type ?? 'standard',
                ]);
            }
            
            if ($request->wantsJson()) {
                return response()->json(['success' => true]);
            }
            return redirect()->route('articles.edit', $articleId)->with('success', 'Preis erfolgreich aktualisiert.');
        }
        
        // Create new price (from inline form)
        if ($request->has('create_new')) {
            $request->validate([
                'price' => 'required|numeric|min:0',
                'valid_from' => 'required|date',
                'valid_until' => 'nullable|date',
                'price_type' => 'nullable|in:selling,purchase,standard',
            ]);
            
            Price::create([
                'article_id' => $articleId,
                'price' => $request->price,
                'valid_from' => $request->valid_from,
                'valid_until' => $request->valid_until ?: null,
                'price_type' => $request->price_type ?? 'standard',
            ]);
            
            if ($request->wantsJson()) {
                return response()->json(['success' => true]);
            }
            return redirect()->route('articles.edit', $articleId)->with('success', 'Preis erfolgreich erstellt.');
        }
        
        // Bulk update (original behavior)
        $prices = $request->input('prices', []);
        
        foreach ($prices as $priceData) {
            if (isset($priceData['id']) && $priceData['id']) {
                $price = Price::find($priceData['id']);
                if ($price) {
                    $price->update([
                        'price' => $priceData['price'],
                        'valid_from' => $priceData['valid_from'],
                        'valid_until' => isset($priceData['valid_until']) && $priceData['valid_until'] ? $priceData['valid_until'] : null,
                        'price_type' => $priceData['price_type'] ?? 'standard',
                        'notes' => $priceData['notes'] ?? null,
                    ]);
                }
            }
        }
        
        return redirect()->route('articles.edit', $articleId)->with('success', 'Preise erfolgreich aktualisiert.');
    }
}

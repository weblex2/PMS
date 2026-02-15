<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestController extends Controller
{
    public function index()
    {
        $guests = DB::table('guests')->orderBy('name')->get();
        return view('guests.index', compact('guests'));
    }

    public function create()
    {
        $countryOptions = Code::getDropdownOptions('de');
        return view('guests.create', compact('countryOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vorname' => 'nullable|string|max:255',
            'nation1' => 'nullable|string|max:10',
            'nation2' => 'nullable|string|max:10',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
        ]);

        // Generate match field using soundex of name + vorname
        $nameForMatch = $validated['name'] . ($validated['vorname'] ? ' ' . $validated['vorname'] : '');
        $match = strtoupper(substr(soundex($nameForMatch), 0, 10));

        DB::table('guests')->insert([
            'name' => $validated['name'],
            'vorname' => $validated['vorname'] ?? null,
            'nation1' => $validated['nation1'] ?? null,
            'nation2' => $validated['nation2'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'match' => $match,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/guests')->with('success', 'Gast erfolgreich erstellt.');
    }

    public function edit($id)
    {
        $guest = DB::table('guests')->where('id', $id)->first();
        
        if (!$guest) {
            return redirect('/guests')->with('error', 'Gast nicht gefunden.');
        }

        $countryOptions = Code::getDropdownOptions('de');
        return view('guests.edit', compact('guest', 'countryOptions'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vorname' => 'nullable|string|max:255',
            'nation1' => 'nullable|string|max:10',
            'nation2' => 'nullable|string|max:10',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
        ]);

        // Generate match field using soundex of name + vorname
        $nameForMatch = $validated['name'] . ($validated['vorname'] ? ' ' . $validated['vorname'] : '');
        $match = strtoupper(substr(soundex($nameForMatch), 0, 10));

        DB::table('guests')
            ->where('id', $id)
            ->update([
                'name' => $validated['name'],
                'vorname' => $validated['vorname'] ?? null,
                'nation1' => $validated['nation1'] ?? null,
                'nation2' => $validated['nation2'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'match' => $match,
                'updated_at' => now(),
            ]);

        return redirect('/guests')->with('success', 'Gast erfolgreich aktualisiert.');
    }

    public function destroy($id)
    {
        DB::table('guests')->where('id', $id)->delete();
        return redirect('/guests')->with('success', 'Gast erfolgreich gelöscht.');
    }
}

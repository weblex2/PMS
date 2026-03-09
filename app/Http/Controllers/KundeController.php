<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KundeController extends Controller
{
    public function index()
    {
        $kunden = DB::table('kunden')->orderBy('name')->get();
        return view('kunden.index', compact('kunden'));
    }

    public function create()
    {
        return view('kunden.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'vorname' => 'nullable|string|max:100',
            'firma'   => 'nullable|string|max:150',
            'email'   => 'nullable|email|max:100',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city'    => 'nullable|string|max:100',
            'notes'   => 'nullable|string',
        ]);

        DB::table('kunden')->insert(array_merge($validated, [
            'created_at' => now(),
            'updated_at' => now(),
        ]));

        return redirect('/kunden')->with('success', 'Kunde erfolgreich erstellt.');
    }

    public function edit($id)
    {
        $kunde = DB::table('kunden')->where('id', $id)->first();
        if (!$kunde) {
            return redirect('/kunden')->with('error', 'Kunde nicht gefunden.');
        }
        return view('kunden.edit', compact('kunde'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'vorname' => 'nullable|string|max:100',
            'firma'   => 'nullable|string|max:150',
            'email'   => 'nullable|email|max:100',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'city'    => 'nullable|string|max:100',
            'notes'   => 'nullable|string',
        ]);

        DB::table('kunden')->where('id', $id)->update(array_merge($validated, [
            'updated_at' => now(),
        ]));

        return redirect('/kunden')->with('success', 'Kunde erfolgreich aktualisiert.');
    }

    public function destroy($id)
    {
        DB::table('kunden')->where('id', $id)->delete();
        return redirect('/kunden')->with('success', 'Kunde erfolgreich gelöscht.');
    }
}

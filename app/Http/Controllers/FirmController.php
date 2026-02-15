<?php

namespace App\Http\Controllers;

use App\Models\Firm;
use Illuminate\Http\Request;

class FirmController extends Controller
{
    public function index()
    {
        $firms = Firm::orderBy('name')->get();
        return view('firms.index', compact('firms'));
    }

    public function create()
    {
        return view('firms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:firms,name',
            'code' => 'required|unique:firms,code',
        ]);

        Firm::create($validated);

        return redirect('/firms')->with('success', 'Haus erfolgreich erstellt.');
    }

    public function edit(Firm $firm)
    {
        return view('firms.edit', compact('firm'));
    }

    public function update(Request $request, Firm $firm)
    {
        $validated = $request->validate([
            'name' => 'required|unique:firms,name,' . $firm->id,
            'code' => 'required|unique:firms,code,' . $firm->id,
            'is_active' => 'boolean',
        ]);

        $firm->update($validated);

        return redirect('/firms')->with('success', 'Haus erfolgreich aktualisiert.');
    }

    public function destroy(Firm $firm)
    {
        $firm->delete();
        return redirect('/firms')->with('success', 'Haus erfolgreich gelöscht.');
    }

    public function switch(Request $request, $id)
    {
        $firm = Firm::findOrFail($id);
        
        if (!$firm->is_active) {
            return back()->with('error', 'Dieses Haus ist nicht aktiv.');
        }

        session(['active_firm_id' => $firm->id]);

        return back()->with('success', 'Zu ' . $firm->name . ' gewechselt.');
    }
}

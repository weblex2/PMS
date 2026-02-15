<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return view("settings.index", compact("settings"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "key" => "required|unique:settings,setting_key",
            "value" => "nullable",
        ]);
        
        Setting::create([
            "setting_key" => $request->key,
            "value" => $request->value,
        ]);
        
        return back()->with("success", "Einstellung gespeichert.");
    }

    public function destroy($key)
    {
        Setting::where("setting_key", $key)->delete();
        return back()->with("success", "Einstellung gelöscht.");
    }
}

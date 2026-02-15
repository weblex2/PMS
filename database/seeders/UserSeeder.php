<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            "name" => "Administrator",
            "email" => "noppenbe@gmx.de",
            "password" => Hash::make("admin123"),
        ]);
    }
}

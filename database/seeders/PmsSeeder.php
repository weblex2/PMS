<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PmsSeeder extends Seeder
{
    public function run()
    {
        // Organization
        $orgId = DB::table("organizations")->insertGetId([
            "name" => "Hotel Musterstadt",
            "slug" => "hotel-musterstadt",
            "email" => "info@hotel-musterstadt.de",
            "phone" => "+49 1234 567890"
        ]);

        // Firm
        $firmId = DB::table("firms")->insertGetId([
            "organization_id" => $orgId,
            "name" => "Hauptbetrieb",
            "code" => "MAIN",
            "email" => "info@hotel-musterstadt.de",
            "tax_number" => "DE123456789"
        ]);

        // Room Types
        $rt1 = DB::table("room_types")->insertGetId(["firm_id" => $firmId, "name" => "Einzelzimmer", "code" => "EZ", "bed_count" => 1, "max_persons" => 1]);
        $rt2 = DB::table("room_types")->insertGetId(["firm_id" => $firmId, "name" => "Doppelzimmer", "code" => "DZ", "bed_count" => 2, "max_persons" => 2]);
        $rt3 = DB::table("room_types")->insertGetId(["firm_id" => $firmId, "name" => "Suite", "code" => "SUITE", "bed_count" => 2, "max_persons" => 4]);

        // Rooms
        $rooms = [
            ["firm_id" => $firmId, "room_type_id" => $rt1, "room_number" => "101", "floor" => "1", "status" => "available"],
            ["firm_id" => $firmId, "room_type_id" => $rt1, "room_number" => "102", "floor" => "1", "status" => "occupied"],
            ["firm_id" => $firmId, "room_type_id" => $rt2, "room_number" => "201", "floor" => "2", "status" => "available"],
            ["firm_id" => $firmId, "room_type_id" => $rt2, "room_number" => "202", "floor" => "2", "status" => "cleaning"],
            ["firm_id" => $firmId, "room_type_id" => $rt3, "room_number" => "301", "floor" => "3", "status" => "available"],
        ];
        foreach ($rooms as $r) DB::table("rooms")->insert($r);

        // Articles
        $arts = [
            ["firm_id" => $firmId, "code" => "UEB", "name" => "Übernachtung EZ", "category" => "Zimmer", "price" => 89.00, "price_type" => "netto", "tax_rate" => 7],
            ["firm_id" => $firmId, "code" => "UEB-DZ", "name" => "Übernachtung DZ", "category" => "Zimmer", "price" => 119.00, "price_type" => "netto", "tax_rate" => 7],
            ["firm_id" => $firmId, "code" => "UEB-SUITE", "name" => "Übernachtung Suite", "category" => "Zimmer", "price" => 199.00, "price_type" => "netto", "tax_rate" => 7],
            ["firm_id" => $firmId, "code" => "FRUEH", "name" => "Frühstück", "category" => "Verpflegung", "price" => 15.00, "price_type" => "netto", "tax_rate" => 19],
            ["firm_id" => $firmId, "code" => "HP", "name" => "Halbpension", "category" => "Verpflegung", "price" => 35.00, "price_type" => "netto", "tax_rate" => 19],
        ];
        foreach ($arts as $a) DB::table("articles")->insert($a);

        // Guests
        $guests = [
            ["firm_id" => $firmId, "salutation" => "Herr", "first_name" => "Max", "last_name" => "Mustermann", "email" => "max@muster.de", "phone" => "+4915012345678", "stay_count" => 5, "last_stay" => "2026-01-15"],
            ["firm_id" => $firmId, "salutation" => "Frau", "first_name" => "Erika", "last_name" => "Musterfrau", "email" => "erika@muster.de", "phone" => "+4915098765432", "stay_count" => 2],
            ["firm_id" => $firmId, "salutation" => "Herr", "first_name" => "John", "last_name" => "Doe", "email" => "john@doe.com", "stay_count" => 1],
        ];
        foreach ($guests as $g) DB::table("guests")->insert($g);

        // Reservations
        $today = date("Y-m-d");
        $res = [
            ["firm_id" => $firmId, "guest_id" => 1, "room_id" => 2, "reservation_number" => "RES-001", "arrival" => $today, "departure" => date("Y-m-d", strtotime("+3 days")), "status" => "checked_in", "adults" => 1],
            ["firm_id" => $firmId, "guest_id" => 2, "room_id" => 4, "reservation_number" => "RES-002", "arrival" => $today, "departure" => date("Y-m-d", strtotime("+2 days")), "status" => "confirmed", "adults" => 2],
            ["firm_id" => $firmId, "guest_id" => 3, "room_id" => 1, "reservation_number" => "RES-003", "arrival" => date("Y-m-d", strtotime("+5 days")), "departure" => date("Y-m-d", strtotime("+7 days")), "status" => "pending", "adults" => 1],
        ];
        foreach ($res as $r) DB::table("reservations")->insert($r);

        echo "Testdaten erstellt!\n";
    }
}

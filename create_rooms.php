<?php
require_once "/var/www/pms/vendor/autoload.php";
 = require_once "/var/www/pms/bootstrap/app.php";
 = ->make(Illuminate\Contracts\Console\Kernel::class);
->bootstrap();

 = [
    ["room_number" => "101", "type" => "single", "bed_count" => 1, "price" => 50.00, "status" => "free", "description" => "Einzelzimmer"],
    ["room_number" => "102", "type" => "single", "bed_count" => 1, "price" => 50.00, "status" => "free", "description" => "Einzelzimmer"],
    ["room_number" => "103", "type" => "single", "bed_count" => 1, "price" => 50.00, "status" => "free", "description" => "Einzelzimmer"],
    ["room_number" => "104", "type" => "single", "bed_count" => 1, "price" => 50.00, "status" => "free", "description" => "Einzelzimmer"],
    ["room_number" => "105", "type" => "single", "bed_count" => 1, "price" => 50.00, "status" => "free", "description" => "Einzelzimmer"],
    ["room_number" => "201", "type" => "double", "bed_count" => 2, "price" => 80.00, "status" => "free", "description" => "Doppelzimmer"],
    ["room_number" => "202", "type" => "double", "bed_count" => 2, "price" => 80.00, "status" => "free", "description" => "Doppelzimmer"],
    ["room_number" => "203", "type" => "double", "bed_count" => 2, "price" => 80.00, "status" => "free", "description" => "Doppelzimmer"],
    ["room_number" => "204", "type" => "double", "bed_count" => 2, "price" => 80.00, "status" => "free", "description" => "Doppelzimmer"],
    ["room_number" => "205", "type" => "double", "bed_count" => 2, "price" => 80.00, "status" => "free", "description" => "Doppelzimmer"],
    ["room_number" => "301", "type" => "triple", "bed_count" => 3, "price" => 110.00, "status" => "free", "description" => "3-Bett Zimmer"],
    ["room_number" => "302", "type" => "triple", "bed_count" => 3, "price" => 110.00, "status" => "free", "description" => "3-Bett Zimmer"],
    ["room_number" => "303", "type" => "triple", "bed_count" => 3, "price" => 110.00, "status" => "free", "description" => "3-Bett Zimmer"],
    ["room_number" => "304", "type" => "triple", "bed_count" => 3, "price" => 110.00, "status" => "free", "description" => "3-Bett Zimmer"],
    ["room_number" => "305", "type" => "triple", "bed_count" => 3, "price" => 110.00, "status" => "free", "description" => "3-Bett Zimmer"],
    ["room_number" => "401", "type" => "4bed", "bed_count" => 4, "price" => 140.00, "status" => "free", "description" => "4-Bett Zimmer"],
    ["room_number" => "402", "type" => "4bed", "bed_count" => 4, "price" => 140.00, "status" => "free", "description" => "4-Bett Zimmer"],
    ["room_number" => "403", "type" => "4bed", "bed_count" => 4, "price" => 140.00, "status" => "free", "description" => "4-Bett Zimmer"],
    ["room_number" => "404", "type" => "4bed", "bed_count" => 4, "price" => 140.00, "status" => "free", "description" => "4-Bett Zimmer"],
    ["room_number" => "405", "type" => "4bed", "bed_count" => 4, "price" => 140.00, "status" => "free", "description" => "4-Bett Zimmer"],
    ["room_number" => "501", "type" => "5bed", "bed_count" => 5, "price" => 170.00, "status" => "free", "description" => "5-Bett Zimmer"],
    ["room_number" => "502", "type" => "5bed", "bed_count" => 5, "price" => 170.00, "status" => "free", "description" => "5-Bett Zimmer"],
    ["room_number" => "503", "type" => "5bed", "bed_count" => 5, "price" => 170.00, "status" => "free", "description" => "5-Bett Zimmer"],
    ["room_number" => "504", "type" => "5bed", "bed_count" => 5, "price" => 170.00, "status" => "free", "description" => "5-Bett Zimmer"],
    ["room_number" => "505", "type" => "5bed", "bed_count" => 5, "price" => 170.00, "status" => "free", "description" => "5-Bett Zimmer"],
    ["room_number" => "601", "type" => "6bed", "bed_count" => 6, "price" => 200.00, "status" => "free", "description" => "6-Bett Zimmer"],
    ["room_number" => "602", "type" => "6bed", "bed_count" => 6, "price" => 200.00, "status" => "free", "description" => "6-Bett Zimmer"],
    ["room_number" => "603", "type" => "6bed", "bed_count" => 6, "price" => 200.00, "status" => "free", "description" => "6-Bett Zimmer"],
    ["room_number" => "604", "type" => "6bed", "bed_count" => 6, "price" => 200.00, "status" => "free", "description" => "6-Bett Zimmer"],
    ["room_number" => "605", "type" => "6bed", "bed_count" => 6, "price" => 200.00, "status" => "free", "description" => "6-Bett Zimmer"],
];

foreach ( as ) {
    App\Models\Room::create();
}

echo "30 Zimmer angelegt!\n";
echo "Total: " . App\Models\Room::count() . " Zimmer\n";

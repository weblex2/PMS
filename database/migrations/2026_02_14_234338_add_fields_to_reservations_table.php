<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table("reservations", function (Blueprint $table) {
            if (!Schema::hasColumn("reservations", "reservation_type")) {
                $table->string("reservation_type")->default("standard")->after("status");
            }
            if (!Schema::hasColumn("reservations", "payment_method")) {
                $table->string("payment_method")->default("cash")->after("payment_status");
            }
            if (!Schema::hasColumn("reservations", "advance_payment")) {
                $table->decimal("advance_payment", 10, 2)->default(0)->after("payment_method");
            }
        });
    }

    public function down(): void
    {
        Schema::table("reservations", function (Blueprint $table) {
            $table->dropColumn(["reservation_type", "payment_method", "advance_payment"]);
        });
    }
};

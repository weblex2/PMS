<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // z.B. "Doppelzimmer", "Einzelzimmer", "4-Bett"
            $table->string('description')->nullable();
            $table->integer('default_bed_count')->default(1);
            $table->decimal('base_price', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('room_types');
    }
};

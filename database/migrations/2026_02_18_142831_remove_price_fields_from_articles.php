<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table("articles", function (Blueprint $table) {
            $table->dropColumn(["purchase_price", "selling_price", "stock"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("articles", function (Blueprint $table) {
            $table->decimal("purchase_price", 10, 2)->nullable();
            $table->decimal("selling_price", 10, 2)->nullable();
            $table->integer("stock", false, true)->default(0);
        });
    }
};

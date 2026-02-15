<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Bettenanzahl zu rooms hinzufügen
        Schema::table('rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('rooms', 'bed_count')) {
                $table->integer('bed_count')->default(1)->after('type');
            }
        });

        // Zimmer-Artikel Verknüpfung (welche Artikel werden im Zimmer verwendet)
        Schema::create('room_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->timestamps();
            
            $table->unique(['room_id', 'article_id']);
        });

        // Preise pro Artikel und Datum
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->date('valid_from');
            $table->date('valid_until')->nullable();
            $table->string('price_type')->default('selling'); // selling, purchase
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['article_id', 'valid_from']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('prices');
        Schema::dropIfExists('room_articles');
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'bed_count')) {
                $table->dropColumn('bed_count');
            }
        });
    }
};

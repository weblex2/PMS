<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Add validity dates
            if (!Schema::hasColumn('rooms', 'valid_from')) {
                $table->date('valid_from')->nullable()->after('description');
            }
            if (!Schema::hasColumn('rooms', 'valid_until')) {
                $table->date('valid_until')->nullable()->after('valid_from');
            }
            // Note: We keep status column but don't use it anymore
        });
    }

    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            if (Schema::hasColumn('rooms', 'valid_from')) {
                $table->dropColumn('valid_from');
            }
            if (Schema::hasColumn('rooms', 'valid_until')) {
                $table->dropColumn('valid_until');
            }
        });
    }
};

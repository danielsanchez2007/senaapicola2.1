<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('apiaries')) {
            return;
        }

        Schema::table('apiaries', function (Blueprint $table) {
            if (! Schema::hasColumn('apiaries', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('location');
            }
            if (! Schema::hasColumn('apiaries', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
            if (! Schema::hasColumn('apiaries', 'image_url')) {
                $table->string('image_url', 512)->nullable()->after('longitude');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('apiaries')) {
            return;
        }

        Schema::table('apiaries', function (Blueprint $table) {
            if (Schema::hasColumn('apiaries', 'image_url')) {
                $table->dropColumn('image_url');
            }
            if (Schema::hasColumn('apiaries', 'longitude')) {
                $table->dropColumn('longitude');
            }
            if (Schema::hasColumn('apiaries', 'latitude')) {
                $table->dropColumn('latitude');
            }
        });
    }
};

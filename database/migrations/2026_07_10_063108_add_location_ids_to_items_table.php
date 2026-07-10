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
        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('city_id')->nullable()->after('type')->constrained()->nullOnDelete();
            $table->foreignId('district_id')->nullable()->after('city_id')->constrained()->nullOnDelete();
            
            $table->dropColumn(['city', 'district']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['items_city_id_foreign']);
            $table->dropForeign(['items_district_id_foreign']);
            $table->dropColumn(['city_id', 'district_id']);
            
            $table->string('city')->nullable()->after('type');
            $table->string('district')->nullable()->after('city');
        });
    }
};

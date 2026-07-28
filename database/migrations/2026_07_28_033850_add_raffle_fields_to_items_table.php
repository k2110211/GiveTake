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
            $table->unsignedInteger('min_karma')->default(0)->after('exchange_wish');
            $table->foreignId('winner_id')->nullable()->after('min_karma')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['winner_id']);
            $table->dropColumn(['min_karma', 'winner_id']);
        });
    }
};

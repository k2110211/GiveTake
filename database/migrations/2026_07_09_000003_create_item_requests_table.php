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
        Schema::create('request_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('color')->nullable();
            $table->timestamps();
        });

        Schema::create('item_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // requester
            $table->text('message');
            $table->foreignId('request_status_id')->default(1)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        // Insert default request statuses
        \Illuminate\Support\Facades\DB::table('request_statuses')->insertOrIgnore([
            ['id' => 1, 'name' => 'Chờ xử lý', 'color' => 'bg-yellow-50 text-yellow-700 border border-yellow-200 dark:bg-yellow-950/20 dark:text-yellow-400 dark:border-yellow-900/30', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Đồng ý', 'color' => 'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/30', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Từ chối', 'color' => 'bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-900/30', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Hủy bởi người cho', 'color' => 'bg-red-50 text-red-600 border border-red-200 dark:bg-red-950/20 dark:text-red-400', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Đã nhận', 'color' => 'bg-teal-50 text-teal-700 border border-teal-200 dark:bg-teal-950/20 dark:text-teal-400', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'name' => 'Đã hủy bởi người nhận', 'color' => 'bg-gray-50 text-gray-500 border border-gray-200 dark:bg-gray-800', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_requests');
        Schema::dropIfExists('request_statuses');
    }
};

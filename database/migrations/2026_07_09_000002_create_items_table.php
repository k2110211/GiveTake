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
        Schema::create('item_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('color')->nullable();
            $table->timestamps();
        });

        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('thumbnail')->nullable();
            $table->json('images');
            $table->foreignId('type_id')->default(1)->constrained()->cascadeOnDelete();
            $table->text('exchange_wish')->nullable();
            $table->foreignId('item_status_id')->default(1)->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('city_id')->default(1)->nullable();
            $table->unsignedBigInteger('district_id')->default(1)->nullable();
            $table->timestamps();
        });

        // Insert default item statuses
        \Illuminate\Support\Facades\DB::table('item_statuses')->insertOrIgnore([
            ['id' => 1, 'name' => 'Có sẵn', 'color' => 'bg-teal-50 text-teal-700 border border-teal-200 dark:bg-teal-950/20 dark:text-teal-400 dark:border-teal-900/30', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Đang yêu cầu', 'color' => 'bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950/20 dark:text-blue-400 dark:border-blue-900/30', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Đang trao đổi', 'color' => 'bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/30', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Trao đổi xong', 'color' => 'bg-gray-50 text-gray-500 border border-gray-200 dark:bg-gray-800/50 dark:text-gray-400 dark:border-gray-700/50', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Insert default item types
        \Illuminate\Support\Facades\DB::table('types')->insertOrIgnore([
            ['id' => 1, 'name' => 'Cho đi', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Trao đổi', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Quay thưởng', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
        Schema::dropIfExists('item_statuses');
        Schema::dropIfExists('types');
    }
};

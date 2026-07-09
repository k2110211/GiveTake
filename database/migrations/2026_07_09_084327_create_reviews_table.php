<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
 
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();   // who writes the review
            $table->foreignId('reviewee_id')->constrained('users')->cascadeOnDelete();   // who receives the review
            $table->tinyInteger('rating');      // 1–5 stars
            $table->text('comment')->nullable();
            $table->timestamps();
 
            // Each user can only review once per transaction
            $table->unique(['item_request_id', 'reviewer_id']);
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('isbn')->unique()->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('publisher')->nullable();
            $table->year('publish_year')->nullable();
            $table->integer('total_copies')->default(1);
            $table->integer('available_copies')->default(1);
            $table->string('shelf_location')->nullable(); // e.g. "A-01", "B-03"
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('language')->default('Indonesia');
            $table->integer('pages')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};

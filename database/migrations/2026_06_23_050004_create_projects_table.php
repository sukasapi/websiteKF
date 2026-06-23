<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->json('title');            // 2 bahasa
            $table->string('slug')->unique();
            $table->string('client')->nullable();
            $table->year('year')->nullable();
            $table->foreignId('project_category_id')->nullable()->constrained()->nullOnDelete();
            $table->json('description');       // 2 bahasa
            $table->json('tech_stack')->nullable(); // array teknologi
            $table->string('cover_image')->nullable();
            $table->string('demo_url')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

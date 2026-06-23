<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->json('title');            // 2 bahasa
            $table->json('description');       // 2 bahasa
            $table->string('icon')->nullable();
            $table->enum('type', ['software', 'animation'])->default('software');
            $table->string('external_url')->nullable(); // untuk layanan animasi (link keluar)
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

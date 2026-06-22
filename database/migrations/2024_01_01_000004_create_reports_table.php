<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->unique(); // SL-2024-0001
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->foreignId('status_id')->constrained()->restrictOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();

            $table->string('title');
            $table->text('description');

            // Lokasi
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('address')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();

            // Visibilitas
            $table->boolean('is_public')->default(true);

            // Data cuaca saat lapor (OpenWeather)
            $table->string('weather_condition')->nullable();
            $table->float('weather_temp')->nullable();
            $table->string('weather_icon')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status_id', 'is_public']);
            $table->index(['latitude', 'longitude']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('luminaires', function (Blueprint $table) {
            $table->id();
            $table->boolean('visible')->default(false);
            $table->string('name');
            $table->json('html_filepath')->default('');
            $table->json('pdf_filepath')->default('');
            $table->foreignId('luminaireFamily_id');
            $table->json('values');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('luminaires');
    }
};

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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('region');
            $table->string('village')->nullable();
            $table->string('crop_type')->nullable();
            $table->boolean('pest_detected')->default(false);
            $table->string('rainfall_status')->nullable(); // low, normal, heavy
            $table->string('crop_condition')->nullable(); // good, average, poor
            $table->decimal('market_price', 8, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};

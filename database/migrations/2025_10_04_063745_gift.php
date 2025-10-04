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
        Schema::create('gifts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the gift
            $table->string('description')->nullable(); // Description of the gift
            $table->decimal('price', 8, 2)->nullable(); // Price of the gift
            $table->date('date_gift')->nullable(); // Date the gift was given
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

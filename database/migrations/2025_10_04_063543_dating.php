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
        // total-date, start-date
        Schema::create('datings', function (Blueprint $table) {
            $table->id();
            $table->date('start_date')->nullable(); // Start date of the dating period
            $table->integer('total_days')->nullable(); // Total number of dating days
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

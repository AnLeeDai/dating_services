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
        // date, content, description
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable(); // Date of the note
            $table->text('content')->nullable(); // Content of the note
            $table->string('description')->nullable(); // Description of the note
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

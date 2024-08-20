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
        Schema::create('puzzle_responses', function (Blueprint $table) {
            $table->id(); // Equivalent to INT AUTO_INCREMENT PRIMARY KEY
            $table->foreignId('puzzle_id')->constrained()->onDelete('cascade'); // Foreign key referencing 'puzzles' table
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key referencing 'user' table
            $table->string('response', 255); // The student's response to the puzzle
            $table->boolean('is_valid'); // Identicates whether the student's response is valid or not
            $table->integer('score')->default(0); // The score for the response
            $table->string('remaining_puzzle_word')->nullable(); // Remaining puzzle word after student response.
            $table->timestamps(); // Created_at and updated_at timestamps
            $table->index(['puzzle_id', 'user_id', 'is_valid']); //  Indexing on specified columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puzzle_responses');
    }
};

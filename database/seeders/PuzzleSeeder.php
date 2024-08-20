<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Traits\DisplaySeederMessageTrait;
use Faker\Factory as Faker;
use App\Models\Puzzle;

/**
 * PuzzleSeeder
 *
 * Seed the puzzles table with 10 puzzle words.
 *
 * @author Abijit <abijit.a.1991@gmail.com>
 *
 * @version 1.0.1
 */
class PuzzleSeeder extends Seeder
{
    use DisplaySeederMessageTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a Faker instance for generating fake data.
        $faker = Faker::create();

        // Loop to create 10 puzzle entries.
        foreach (range(1, 10) as $index) {
            // Create a new puzzle entry with a generated puzzle word.
            Puzzle::create([
                'puzzle_word' => $this->generatePuzzleWord($faker)
            ]);
        }

        // Display a message after seeding is complete.
        $this->displayMessage(config('constants.SEEDER_SUCCESS_MSG.PUZZLE_SEEDER'));
    }

    /**
     * Generates a unique puzzle word using Faker.
     *
     * @param object $faker The Faker instance used to generate random data.
     *
     * @return string A unique puzzle word.
     */
    public function generatePuzzleWord(object $faker): string
    {
        // Generate a random string with 16 characters, in lowercase.
        $puzzleWord = trim(strtolower($faker->lexify('????????????????')));

        // Check if the generated word already exists in the database.
        // If it does, recursively generate a new word.
        return !$this->isPuzzleWordExists($puzzleWord) ? $puzzleWord : $this->generatePuzzleWord($faker);
    }

    /**
     * Check if a puzzle word already exists in the database.
     *
     * @param string $puzzleWord The puzzle word to check for existence.
     *
     * @return bool True if the puzzle word exists, false otherwise.
     */
    public function isPuzzleWordExists(string $puzzleWord): bool
    {
        // Check the given puzzle word exists.
        // If exists return true otherwise false.
        return Puzzle::where('puzzle_word', $puzzleWord)->exists();
    }
}

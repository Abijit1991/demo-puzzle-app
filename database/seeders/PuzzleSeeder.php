<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Traits\DisplaySeederMessageTrait;
use Faker\Factory as Faker;
use App\Models\Puzzle;

class PuzzleSeeder extends Seeder
{
    use DisplaySeederMessageTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            Puzzle::create([
                'puzzle_word' => $this->generatePuzzleWord($faker)
            ]);
        }

        // Display a message.
        $this->displayMessage(config('constants.SEEDER_SUCCESS_MSG.PUZZLE_SEEDER'));
    }

    public function generatePuzzleWord(object $faker): string
    {
        $puzzleWord = trim(strtolower($faker->lexify('????????????????')));
        return !$this->isPuzzleWordExists($puzzleWord) ? $puzzleWord : $this->generatePuzzleWord($faker);
    }

    public function isPuzzleWordExists(string $puzzleWord): bool
    {
        return Puzzle::where('puzzle_word', $puzzleWord)->exists();
    }
}

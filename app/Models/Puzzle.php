<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Puzzle Model
 *
 * @author Abijit <abijit.a.1991@gmail.com>
 *
 * @version 1.0.1
 */
class Puzzle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'puzzle_word',
    ];

    /**
     * Get the puzzle responses associated with the puzzle.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function puzzleresponses(): HasMany
    {
        return $this->hasMany(PuzzleResponse::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * PuzzleResponse Model
 *
 * @author Abijit <abijit.a.1991@gmail.com>
 *
 * @version 1.0.1
 */
class PuzzleResponse extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'puzzle_responses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'puzzle_id',
        'user_id',
        'response',
        'is_valid',
        'score',
        'remaining_puzzle_word'
    ];

    /**
     * The attributes that should be cast to dates.
     *
     * @var array<int, string>
     */
    protected $dates = [
        'updated_at',
    ];

    /**
     * Get the puzzle associated with the puzzle response.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function puzzle(): BelongsTo
    {
        return $this->belongsTo(Puzzle::class);
    }

    /**
     * Get the user associated with the puzzle response.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\PuzzleResponse;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class PuzzleServices
{
    public static function getPuzzleResponseDetails($puzzle)
    {
        $puzzleResponses = self::getAllPuzzleResponses($puzzle->id);

        $puzzleWord = !empty(count($puzzleResponses) && self::getValidPuzzleResponseCount($puzzleResponses))
            ? $puzzleResponses->where('is_valid', true)->first()->remaining_puzzle_word
            : $puzzle->puzzle_word;

        return [
            $puzzleWord,
            $puzzleResponses
        ];
    }

    public static function getAllPuzzleResponses($puzzleId)
    {
        return PuzzleResponse::where([
            'puzzle_id' => $puzzleId,
            'user_id' => Auth::user()->id
        ])->latest('id')
            ->get(['response', 'is_valid', 'score', 'remaining_puzzle_word', 'updated_at']);
    }

    public static function getValidPuzzleResponseCount($puzzleResponses)
    {
        return $puzzleResponses->where('is_valid', true)->count();
    }

    public static function getLatestValidRemainingPuzzleWord($puzzleId)
    {
        return PuzzleResponse::where([
            'puzzle_id' => $puzzleId,
            'user_id' => Auth::user()->id,
            'is_valid' => true
        ])->latest('id')
            ->value('remaining_puzzle_word');
    }

    public static function validateResponse($response)
    {
        // Create a new Guzzle client instance
        $client = new Client();

        // Define the API endpoint
        $api = config('constants.FREE_DICTIONARY_API') . $response;

        try {
            // Send a GET request to the API
            $response = $client->request('GET', $api);
        } catch (\Exception) {
            // Return false if any occurs.
            return false;
        }

        // Return true if success.
        return true;
    }

    public static function updatePuzzleWordWithResponse($puzzleWord = null, $response = null)
    {
        if (!empty($puzzleWord) && !empty($response)) {
            foreach (str_split($response) as $letter) {
                $position = strpos($puzzleWord, $letter);
                if ($position !== false) {
                    $puzzleWord = substr_replace($puzzleWord, '', $position, 1);
                }
            }
        }

        return $puzzleWord;
    }

    public static function checkPuzzleWordWithResponse($puzzleWord = null, $response = null)
    {
        $matchWordCount = 0;

        if (!empty($puzzleWord) && !empty($response)) {
            foreach (str_split($response) as $letter) {
                $position = strpos($puzzleWord, $letter);
                if ($position !== false) {
                    $puzzleWord = substr_replace($puzzleWord, '', $position, 1);
                    $matchWordCount++;
                }
            }
        }

        return [
            $matchWordCount == strlen($response) ? true : false,
            $puzzleWord
        ];
    }

    public static function showPuzzleTopperDetails($puzzleId)
    {
        return PuzzleResponse::select('user_id', DB::raw('SUM(score) as total_score'))
            ->where([
                'puzzle_id' => $puzzleId,
                'is_valid' => true
            ])->groupBy('user_id')
            ->orderBy('total_score', 'desc')
            ->limit(config('constants.TOP_SCORERS_LIMIT'))
            ->get();
    }

    public static function showTopperDetails()
    {
        return PuzzleResponse::select('user_id', DB::raw('SUM(score) as total_score'), DB::raw('COUNT(puzzle_id) as puzzle_count'))
            ->where([
                'is_valid' => true
            ])->groupBy('user_id')
            ->orderBy('total_score', 'desc')
            ->limit(config('constants.TOP_SCORERS_LIMIT'))
            ->get();
    }
}

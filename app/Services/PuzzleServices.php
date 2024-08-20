<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\PuzzleResponse;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

/**
 * PuzzleServices
 *
 * Helper Service class.
 *
 * @author Abijit <abijit.a.1991@gmail.com>
 *
 * @version 1.0.1
 */
class PuzzleServices
{
    /**
     * Retrieve the puzzle word and its associated responses.
     *
     * @param \App\Models\Puzzle $puzzle The puzzle instance for which details are retrieved.
     *
     * @return array An array containing the current puzzle word and a collection of puzzle responses.
     */
    public static function getPuzzleResponseDetails($puzzle)
    {
        // Retrieve all responses for the given puzzle.
        $puzzleResponses = self::getAllPuzzleResponses($puzzle->id);

        // Determine the puzzle word based on the validation.
        $puzzleWord = !empty(count($puzzleResponses) && self::getValidPuzzleResponseCount($puzzleResponses))
            ? $puzzleResponses->where('is_valid', true)->first()->remaining_puzzle_word
            : $puzzle->puzzle_word;

        // Return the data.
        return [
            $puzzleWord,
            $puzzleResponses
        ];
    }

    /**
     * Retrieve all responses for a given puzzle made by the authenticated user.
     *
     * @param int $puzzleId The ID of the puzzle for which responses are retrieved.
     *
     * @return \Illuminate\Database\Eloquent\Collection A collection of puzzle responses with selected fields.
     */
    public static function getAllPuzzleResponses($puzzleId)
    {
        // Return the data.
        return PuzzleResponse::where([
            'puzzle_id' => $puzzleId,
            'user_id' => Auth::user()->id
        ])->latest('id')
            ->get(['response', 'is_valid', 'score', 'remaining_puzzle_word', 'updated_at']);
    }

    /**
     * Count the number of valid puzzle responses from a collection.
     *
     * @param \Illuminate\Database\Eloquent\Collection $puzzleResponses A collection of puzzle responses.
     *
     * @return int The number of valid puzzle responses.
     */
    public static function getValidPuzzleResponseCount($puzzleResponses)
    {
        // Return the data.
        return $puzzleResponses->where('is_valid', true)->count();
    }

    /**
     * Retrieve the latest valid remaining puzzle word for a given puzzle and user.
     *
     * @param int $puzzleId The ID of the puzzle for which the remaining puzzle word is retrieved.
     *
     * @return string|null The remaining puzzle word from the latest valid response, or null if no valid responses exist.
     */
    public static function getLatestValidRemainingPuzzleWord($puzzleId)
    {
        // Return the data.
        return PuzzleResponse::where([
            'puzzle_id' => $puzzleId,
            'user_id' => Auth::user()->id,
            'is_valid' => true
        ])->latest('id')
            ->value('remaining_puzzle_word');
    }

    /**
     * Validate a response by checking it against an external dictionary API.
     *
     * @param string $response The word or response to be validated.
     *
     * @return bool True if the response is valid according to the API, false otherwise.
     */
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

    /**
     * Check if the given response matches the puzzle word and return the result.
     *
     * @param string|null $puzzleWord The puzzle word to be matched against. Defaults to null.
     * @param string|null $response The response to be checked. Defaults to null.
     *
     * @return array An array where the first element is a boolean indicating if the response matches
     *               the puzzle word, and the second element is the updated puzzle word.
     */
    public static function checkPuzzleWordWithResponse($puzzleWord = null, $response = null)
    {
        // Initialize the count of matched letters.
        $matchWordCount = 0;

        // Check if both are not empty.
        if (!empty($puzzleWord) && !empty($response)) {
            // Iterate through each letter in the response.
            foreach (str_split($response) as $letter) {
                // Find the position of the letter in the puzzle word.
                $position = strpos($puzzleWord, $letter);
                if ($position !== false) {
                    // Remove the matched letter from the puzzle word.
                    $puzzleWord = substr_replace($puzzleWord, '', $position, 1);
                    // Increment the count of matched letters.
                    $matchWordCount++;
                }
            }
        }

        // Return the data.
        return [
            $matchWordCount == strlen($response) ? true : false,
            $puzzleWord
        ];
    }

    /**
     * Retrieve details of the top scorers for a given puzzle.
     *
     * @param int $puzzleId The ID of the puzzle for which top scorers are retrieved.
     *
     * @return \Illuminate\Database\Eloquent\Collection A collection of top scorers with their user IDs and total scores.
     */
    public static function showPuzzleTopperDetails($puzzleId)
    {
        // Return the data.
        return PuzzleResponse::select('user_id', DB::raw('SUM(score) as total_score'))
            ->where([
                'puzzle_id' => $puzzleId,
                'is_valid' => true
            ])->groupBy('user_id')
            ->orderBy('total_score', 'desc')
            ->limit(config('constants.TOP_SCORERS_LIMIT'))
            ->get();
    }

    /**
     * Retrieve details of the top scorers based on valid puzzle responses.
     *
     * @return \Illuminate\Database\Eloquent\Collection A collection of top scorers with their user IDs, total scores, and puzzle counts.
     */
    public static function showTopperDetails()
    {
        // Return the data.
        return PuzzleResponse::select('user_id', DB::raw('SUM(score) as total_score'), DB::raw('COUNT(puzzle_id) as puzzle_count'))
            ->where([
                'is_valid' => true
            ])->groupBy('user_id')
            ->orderBy('total_score', 'desc')
            ->limit(config('constants.TOP_SCORERS_LIMIT'))
            ->get();
    }
}

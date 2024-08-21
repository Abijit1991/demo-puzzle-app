<?php

namespace App\Services;

use App\Models\Puzzle;
use Illuminate\Support\Facades\Auth;
use App\Models\PuzzleResponse;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

/**
 * PuzzleResponseServices
 *
 * Helper Service class.
 *
 * @author Abijit <abijit.a.1991@gmail.com>
 *
 * @version 1.0.3
 */
class PuzzleResponseServices
{
    /**
     * Retrieve the puzzle word and its associated responses.
     *
     * @param int $puzzleId The ID of the puzzle to retrieve.
     *
     * @return array An array containing the current puzzle word and a collection of puzzle responses.
     */
    public static function getPuzzleResponseDetails($puzzleId)
    {
        // Retrieve the details of a puzzle by its ID.
        $puzzle = self::getPuzzleDetails($puzzleId);

        // Retrieve all valid puzzle responses for the given puzzle.
        $puzzleResponses = $puzzle->puzzleresponses()->where([
            'user_id' => Auth::user()->id
        ])->latest('id')
            ->get(['response', 'is_valid', 'score', 'remaining_puzzle_word', 'updated_at']);

        // Determine the puzzle word based on the validation.
        $puzzleWord = !empty(count($puzzleResponses) && self::getValidPuzzleResponseCount($puzzleResponses))
            ? $puzzleResponses->where('is_valid', true)->first()->remaining_puzzle_word
            : $puzzle->puzzle_word;

        // Return the data.
        return [
            $puzzle,
            $puzzleWord,
            $puzzleResponses
        ];
    }

    /**
     * Retrieve the details of a puzzle by its ID.
     *
     * @param int $puzzleId The ID of the puzzle to retrieve.
     *
     * @return \App\Models\Puzzle|null The Puzzle model instance or null if not found.
     */
    public static function getPuzzleDetails($puzzleId)
    {
        // Return the data.
        return Puzzle::find($puzzleId);
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
     * Save a user's response to a puzzle.
     *
     * @param \Illuminate\Http\Request $request The incoming request containing the puzzle ID and user's response.
     *
     * @return void
     */
    public static function savePuzzleResponse($request)
    {
        // Retrieve the puzzle from the database using the provided ID.
        $puzzle = self::getPuzzleDetails($request->input('puzzle_id'));

        // Retrieve the user's response from the request.
        $response = $request->input('response');

        // Get the latest puzzle word.
        $latestPuzzleWord = self::getLatestValidRemainingPuzzleWord($puzzle) ?? $puzzle->puzzle_word;

        // Get the word match found status and puzzleword.
        list($isMatchFound, $puzzleWord) = self::checkPuzzleWordWithResponse($latestPuzzleWord, $response);

        // If a match is found, validate the response and save it in the database.
        if ($isMatchFound) {
            // Validate the response to check its correctness.
            $isValid = self::validateResponse($request->input('response'));

            // Create a record with the relevant details.
            PuzzleResponse::create([
                'puzzle_id' => $puzzle->id,
                'user_id' => Auth::user()->id,
                'response' => $response,
                'is_valid' => $isValid,
                'score' => $isValid ? strlen($response) : 0,
                'remaining_puzzle_word' => $isValid ? $puzzleWord : $latestPuzzleWord
            ]);
        }
    }

    /**
     * Retrieve the latest valid remaining puzzle word for a given puzzle and user.
     *
     * @param mixed $puzzle The puzzle for which the remaining puzzle word is retrieved.
     *
     * @return string|null The remaining puzzle word from the latest valid response, or null if no valid responses exist.
     */
    public static function getLatestValidRemainingPuzzleWord($puzzle)
    {
        // Return the data
        return $puzzle->puzzleresponses()->where([
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

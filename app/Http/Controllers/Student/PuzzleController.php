<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Puzzle;
use App\Models\PuzzleResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\PuzzleResponseServices;
use Illuminate\Support\Facades\Validator;

/**
 * HomeController
 *
 * @author Abijit <abijit.a.1991@gmail.com>
 *
 * @version 1.0.1
 */
class PuzzleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the details of a specific puzzle.
     *
     * @param \Illuminate\Http\Request $request The HTTP request.
     * @param int $id The ID of the puzzle to be displayed.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showPuzzle(Request $request, $puzzleId)
    {
        // Merge new input into the incoming request
        $request->merge([
            'id' => $puzzleId
        ]);

        // Validate the request data.
        $validator = Validator::make($request->all(), [
            'id' => 'bail|required|integer|exists:puzzles,id'
        ]);

        // If validation fails, redirect the concerned page.
        if ($validator->fails()) {
            return redirect()->route('student.home');
        }

        // Get the puzzle word and its associated responses using a service method.
        list($puzzle, $puzzleWord, $puzzleResponses) = PuzzleResponseServices::getPuzzleResponseDetails($puzzleId);

        // Return the concerned view blade
        return view('student.showpuzzle', compact('puzzle', 'puzzleWord', 'puzzleResponses'));
    }

    /**
     * Handle the saving of a puzzle response.
     *
     * @param \Illuminate\Http\Request $request The HTTP request.
     *
     * @return \Illuminate\Http\RedirectResponse Redirects to the concerned page.
     */
    public function savePuzzleResponse1(Request $request)
    {
        // Validate the request data.
        $validator = Validator::make($request->all(), [
            'puzzle_id' => 'bail|required|integer|exists:puzzles,id',
            'response' => 'bail|required|alpha'
        ]);

        // If validation fails, redirect to the concerned page.
        if ($validator->fails()) {
            return redirect()->route('student.home');
        }

        // Retrieve the user's response from the request.
        $response = $request->input('response');

        // Retrieve the puzzle from the database using the provided ID.
        $puzzle = Puzzle::find($request->input('puzzle_id'));

        // Get the latest puzzle word.
        $latestPuzzleWord = PuzzleResponseServices::getLatestValidRemainingPuzzleWord($puzzle->id) ?? $puzzle->puzzle_word;

        // Get the word match found status and puzzleword.
        list($isMatchFound, $puzzleWord) = PuzzleResponseServices::checkPuzzleWordWithResponse($latestPuzzleWord, $response);

        // If a match is found, validate the response and save it in the database.
        if ($isMatchFound) {
            // Validate the response to check its correctness.
            $isValid = PuzzleResponseServices::validateResponse($request->input('response'));

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

        // Redirect to the concerned page.
        return redirect()->route('student.showpuzzle', ['puzzle_id' => $puzzle->id]);
    }

    /**
     * Handle the saving of a puzzle response.
     *
     * @param \Illuminate\Http\Request $request The HTTP request.
     *
     * @return \Illuminate\Http\RedirectResponse Redirects to the concerned page.
     */
    public function savePuzzleResponse(Request $request)
    {
        // Validate the request data.
        $validator = Validator::make($request->all(), [
            'puzzle_id' => 'bail|required|integer|exists:puzzles,id',
            'response' => 'bail|required|alpha'
        ]);

        // If validation fails, redirect to the concerned page.
        if ($validator->fails()) {
            return redirect()->route('student.home');
        }

        // Save the puzzle response data.
        PuzzleResponseServices::savePuzzleResponse($request);

        // Redirect to the concerned page.
        return redirect()->route('student.showpuzzle', ['puzzle_id' => $request->input('puzzle_id')]);
    }

    /**
     * Display the top scorer user details of a specific puzzle.
     *
     * @param \Illuminate\Http\Request $request The HTTP request.
     * @param int $id The ID of the puzzle to be displayed.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showPuzzleToppers(Request $request, $puzzleId)
    {
        // Merge new input into the incoming request
        $request->merge([
            'id' => $puzzleId
        ]);

        // Validate the request data.
        $validator = Validator::make($request->all(), [
            'id' => 'bail|required|integer|exists:puzzles,id'
        ]);

        // If validation fails, redirect the concerned page.
        if ($validator->fails()) {
            return redirect()->route('student.home');
        }

        // Retrieve the puzzle from the database using the provided ID.
        $puzzle = PuzzleResponseServices::getPuzzleDetails($puzzleId);

        // Fetch the top scorers for the puzzle based on the provided ID.
        $puzzleTopperDetails = PuzzleResponseServices::showPuzzleTopperDetails($puzzle->id);

        // Return the concerned view blade.
        return view('student.showpuzzletopscorers', compact('puzzle', 'puzzleTopperDetails'));
    }

    /**
     * Display the overall top scorer user details of the puzzles.
     *
     * @param \Illuminate\Http\Request $request The HTTP request.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showToppersList(Request $request)
    {
        // Fetch the top scorers for all the puzzle.
        $puzzleTopperDetails = PuzzleResponseServices::showTopperDetails();

        // Return the concerned view blade.
        return view('student.showtopscorers', compact('puzzleTopperDetails'));
    }
}

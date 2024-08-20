<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Puzzle;
use App\Models\PuzzleResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\PuzzleServices;
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
    public function showPuzzle(Request $request, $id)
    {
        // Add to the request data for validation purposes.
        $request->request->add([
            'id' => $id
        ]);

        // Validate the request data.
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:puzzles,id'
        ]);

        // If validation fails, redirect the concerned page.
        if ($validator->fails()) {
            return redirect()->route('student.home');
        }

        // Retrieve the puzzle from the database using the provided ID.
        $puzzle = Puzzle::find($id);

        // Get the puzzle word and its associated responses using a service method.
        list($puzzleWord, $puzzleResponses) = PuzzleServices::getPuzzleResponseDetails($puzzle);

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
    public function savePuzzleResponse(Request $request)
    {
        // Validate the request data.
        $validator = Validator::make($request->all(), [
            'puzzle_id' => 'required|integer|exists:puzzles,id',
            'response' => 'required|alpha'
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
        $latestPuzzleWord = PuzzleServices::getLatestValidRemainingPuzzleWord($puzzle->id) ?? $puzzle->puzzle_word;

        // Get the word match found status and puzzleword.
        list($isMatchFound, $puzzleWord) = PuzzleServices::checkPuzzleWordWithResponse($latestPuzzleWord, $response);

        // If a match is found, validate the response and save it in the database.
        if ($isMatchFound) {
            // Validate the response to check its correctness.
            $isValid = PuzzleServices::validateResponse($request->input('response'));

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
     * Display the top scorer user details of a specific puzzle.
     *
     * @param \Illuminate\Http\Request $request The HTTP request.
     * @param int $id The ID of the puzzle to be displayed.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showPuzzleToppers(Request $request, $id)
    {
        // Add to the request data for validation purposes.
        $request->request->add([
            'id' => $id
        ]);

        // Validate the request data.
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:puzzles,id'
        ]);

        // If validation fails, redirect the concerned page.
        if ($validator->fails()) {
            return redirect()->route('student.home');
        }

        // Retrieve the puzzle from the database using the provided ID.
        $puzzle = Puzzle::find($id);

        // Fetch the top scorers for the puzzle based on the provided ID.
        $puzzleTopperDetails = PuzzleServices::showPuzzleTopperDetails($puzzle->id);

        // Return the concerned view blade.
        return view('student.showpuzzletopscorers', compact('puzzle', 'puzzleTopperDetails'));
    }

    /**
     * Display the top scorer user details of all puzzles.
     *
     * @param \Illuminate\Http\Request $request The HTTP request.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showToppersList()
    {
        // Fetch the top scorers for all the puzzle.
        $puzzleTopperDetails = PuzzleServices::showTopperDetails();

        // Return the concerned view blade.
        return view('student.showtopscorers', compact('puzzleTopperDetails'));
    }
}

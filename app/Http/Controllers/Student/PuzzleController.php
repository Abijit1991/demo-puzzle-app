<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Puzzle;
use App\Models\PuzzleResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\PuzzleServices;

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
     * Show the puzzle.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showPuzzle($id)
    {
        $puzzle = Puzzle::find($id);

        list($puzzleWord, $puzzleResponses) = PuzzleServices::getPuzzleResponseDetails($puzzle);

        return view('student.showpuzzle', compact('puzzle', 'puzzleWord', 'puzzleResponses'));
    }

    public function savePuzzleResponse(Request $request)
    {
        $response = $request->input('response');

        $puzzle = Puzzle::find($request->input('puzzle_id'));

        $latestPuzzleWord = PuzzleServices::getLatestValidRemainingPuzzleWord($puzzle->id) ?? $puzzle->puzzle_word;

        list($isMatchFound, $puzzleWord) = PuzzleServices::checkPuzzleWordWithResponse($latestPuzzleWord, $response);

        if ($isMatchFound) {
            $isValid = PuzzleServices::validateResponse($request->input('response'));

            PuzzleResponse::create([
                'puzzle_id' => $puzzle->id,
                'user_id' => Auth::user()->id,
                'response' => $response,
                'is_valid' => $isValid,
                'score' => $isValid ? strlen($response) : 0,
                'remaining_puzzle_word' => $isValid ? $puzzleWord : $latestPuzzleWord
            ]);
        }

        return redirect()->route('student.showpuzzle', ['puzzle_id' => $puzzle->id]);
    }

    public function showPuzzleToppers($id)
    {
        $puzzle = Puzzle::find($id);
        $puzzleTopperDetails = PuzzleServices::showTopperDetails($puzzle->id);

        return view('student.showpuzzletopscorers', compact('puzzle', 'puzzleTopperDetails'));
    }
}

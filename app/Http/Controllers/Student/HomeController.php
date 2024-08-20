<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Puzzle;

/**
 * HomeController
 *
 * @author Abijit <abijit.a.1991@gmail.com>
 *
 * @version 1.0.1
 */
class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $puzzles = Puzzle::orderBy('id')->get(['id']);

        return view('student.home', compact('puzzles'));
    }
}

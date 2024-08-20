@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('student.home') }}">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Puzzle</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            {{ __('Student Dashboard') }}
                        </h5>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-sm-12">
                                <p class="text-end">
                                    <a href="#" class="btn btn-primary btn-sm">
                                        <i class="bi bi-award-fill"></i>&nbsp; Puzzle Top Scorers
                                    </a>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <table class="table table-bordered table-hover table-sm">
                                <thead class="table-primary">
                                    <tr class="text-center">
                                        <th scope="col">#</th>
                                        <th scope="col">Puzzle Link</th>
                                        <th scope="col">Puzzle Top Scores</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sno = 1;
                                    @endphp
                                    @foreach ($puzzles as $puzzle)
                                        <tr class="text-center">
                                            <td scope="row">{{ $sno }}</td>
                                            <td scope="row">
                                                <a href="{{ route('student.showpuzzle', ['puzzle_id' => $puzzle->id]) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="bi bi-puzzle-fill"></i>&nbsp; Puzzle
                                                </a>
                                            </td>
                                            <td scope="row">
                                                <a href="{{ route('student.showpuzzlestoppers', ['puzzle_id' => $puzzle->id]) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="bi bi-person-lines-fill"></i>&nbsp; Top Scorers
                                                </a>
                                            </td>
                                        </tr>
                                        @php
                                            $sno++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

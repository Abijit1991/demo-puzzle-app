@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            {{ __('Puzzle: #') }}
                            <span class="text-primary">{{ $puzzle->id }}</span>
                        </h5>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-12">
                                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('student.home') }}">Home</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Puzzle</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <h5 class="card-title">
                            {{ __('Puzzle Word : ') }}
                            <span class="text-primary">{{ $puzzleWord }}</span>
                        </h5>
                        <hr />

                        <div class="row">
                            <div class="col-md-4">
                                <form action="{{ route('student.save.puzzle.response') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="puzzle_id" value="{{ $puzzle->id }}" />
                                    <div class="form-group row">
                                        <label for="response" class="col-sm-4 col-form-label">Enter your
                                            responses</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="response" name="response"
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-10">
                                            <button type="submit" class="btn btn-primary">Submit Response</button>
                                            <button type="reset" class="btn btn-danger">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-8">
                                <table class="table table-bordered table-hover table-sm">
                                    <thead class="table-primary">
                                        <tr class="text-center">
                                            <th scope="col">#</th>
                                            <th scope="col">Submitted At</th>
                                            <th scope="col">Submitted Response</th>
                                            <th scope="col">Valid Status</th>
                                            <th scope="col">Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalScore = 0;
                                            $sno = 1;
                                        @endphp
                                        @if (!empty($puzzleResponses->count()))
                                            @foreach ($puzzleResponses as $puzzleResponse)
                                                @php
                                                    $class = 'bg-danger';
                                                    $status = 'Invalid';
                                                    if ($puzzleResponse->is_valid) {
                                                        $class = 'bg-success';
                                                        $status = 'Valid';
                                                        $totalScore += $puzzleResponse->score;
                                                    }
                                                @endphp
                                                <tr class="text-center">
                                                    <td scope="row">{{ $sno }}</td>
                                                    <td scope="row">
                                                        {{ $puzzleResponse->updated_at->format('d-m-Y h:i A') }}
                                                    </td>
                                                    <td scope="row" class="text-primary text-bold">
                                                        {{ $puzzleResponse->response }}
                                                    </td>
                                                    <td scope="row">
                                                        <span class="badge {{ $class }}">
                                                            {{ $status }}
                                                        </span>
                                                    </td>
                                                    <td scope="row">
                                                        <span class="badge {{ $class }}">
                                                            {{ $puzzleResponse->score }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                @php
                                                    $sno++;
                                                @endphp
                                            @endforeach
                                        @else
                                            <tr class="text-center">
                                                <th scope="col">-</th>
                                                <th scope="col">-</th>
                                                <th scope="col">-</th>
                                                <th scope="col">-</th>
                                                <th scope="col">-</th>
                                            </tr>
                                        @endif
                                    </tbody>
                                    <tfoot class="table-primary">
                                        <tr class="text-center">
                                            <th scope="col" colspan="4">Total Score</th>
                                            <th scope="col">{{ $totalScore }}</th>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#response').on('keypress', function(e) {
                var charCode = e.which;
                // Allow backspace, delete, arrow keys, and tab
                if (charCode == 8 || charCode == 46 || charCode == 37 || charCode == 39 || charCode == 9) {
                    return true;
                }
                // Ensure that the pressed key is a lowercase letter (a-z)
                if (charCode >= 97 && charCode <= 122) {
                    return true;
                }
                // Prevent default behavior (character will not be inserted)
                return false;
            });
        });
    </script>
@endsection

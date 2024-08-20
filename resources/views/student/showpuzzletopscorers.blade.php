@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
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
            <div class="col-md-8">
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

                        <h5 class="card-title">
                            {{ __('Puzzle Word : ') }}
                            <span class="text-primary">{{ $puzzle->puzzle_word }}</span>
                        </h5>
                        <hr />

                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered table-hover table-sm">
                                    <thead class="table-primary">
                                        <tr class="text-center">
                                            <th scope="col">#</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Email ID</th>
                                            <th scope="col">Scores</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $sno = 1;
                                        @endphp
                                        @if (!empty($puzzleTopperDetails->count()))
                                            @foreach ($puzzleTopperDetails as $topperDetail)
                                                <tr class="text-center">
                                                    <td scope="row">{{ $sno }}</td>
                                                    <td scope="row" class="text-primary text-bold">
                                                        {{ $topperDetail->user->name }}
                                                    </td>
                                                    <td scope="row" class="text-primary text-bold">
                                                        {{ $topperDetail->user->email }}
                                                    </td>
                                                    <td scope="row">
                                                        <span class="badge bg-success">
                                                            {{ $topperDetail->total_score }}
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
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

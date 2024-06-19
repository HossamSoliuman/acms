@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title text-center">Meeting Scheduled Successfully!</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-center">Your meeting has been successfully scheduled.</p>
                        <div class="meeting-details">
                            <h4 class="mb-4 text-center">Meeting Details</h4>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong>Engineer:</strong> {{ $meeting->eng->name }}
                                </li>
                                <li class="list-group-item">
                                    <strong>User:</strong> {{ $meeting->user->name }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Start Time:</strong>
                                    {{ \Carbon\Carbon::parse($meeting->start_at)->format('d M Y, h:i A') }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Meeting URL:</strong> <a href="{{ $meeting->url }}"
                                        target="_blank">{{ $meeting->url }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card {
            border-radius: 10px;
        }

        .card-header {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .card-footer {
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .list-group-item {
            font-size: 16px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
@endsection

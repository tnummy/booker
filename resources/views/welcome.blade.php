@extends('layouts.app')

@section('content')
    <div class="flex-center position-ref full-height">
        @if (Route::has('login'))
            <div class="top-right links">
                @auth
                    <a href="{{ route('home') }}">Home</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        @endif

        <div class="content bg-default border-radius padding">
            <div>
                Welcome To:
            </div>
            <div class="title m-b-md">
                Booker
            </div>

            @auth
                <div class="links">
                    <a href="{{ route('home') }}">Dashboard</a>
                </div>
            @else
                <div class="links">
                    <a href="{{ route('login') }}">Login</a>
                    |
                    <a href="{{ route('register') }}">Register</a>
                    |
                    <a href="{{ route('asteroids') }}">Play Asteroids</a>
                </div>
            @endauth

        </div>
    </div>
@endsection

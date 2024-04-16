@extends('layouts.app')
@section('content')
    <div class="row">
        @include('layouts.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            @yield('content')
        </main>
    </div>
@endsection

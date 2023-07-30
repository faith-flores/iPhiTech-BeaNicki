@extends('layouts.base')

@section('body')
    <div class="grid xl:grid-cols-2 min-h-screen bg-gray-50">
        <div class="flex flex-col align-middle justify-center bg-white">
            <h1 class="text-9xl font-black text-center">BeaNicki</h1>
        </div>
        <div class="flex flex-col justify-center">
            @yield('content')

            @isset($slot)
                {{ $slot }}
            @endisset
        </div>
    </div>
@endsection

@extends('layouts.base')

@section('body')
    <div class="grid min-h-screen bg-gray-50 overflow-clip">
        <div class="flex flex-col justify-center items-center h-screen p-10">
            @yield('content')

            @isset($slot)
                {{ $slot }}
            @endisset
        </div>
    </div>
@endsection

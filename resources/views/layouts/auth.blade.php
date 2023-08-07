@extends('layouts.base')

@section('body')
    <div class="grid xl:grid-cols-2 min-h-screen bg-gray-50 overflow-clip">
        <div class="flex flex-col align-middle justify-center bg-white">
            <h1 class="text-9xl font-black text-center">BeaNicki</h1>
        </div>
        <div class="flex flex-col overflow-y-auto h-screen p-10">
            @yield('content')

            @isset($slot)
                {{ $slot }}
            @endisset
        </div>
    </div>
@endsection

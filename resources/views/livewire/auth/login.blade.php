@section('title', 'Sign in to your account')

<div>
    <div class="fi-simple-main my-16 w-full bg-white px-6 py-12 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 sm:max-w-lg sm:rounded-xl sm:px-12">
        <section class="grid auto-cols-fr gap-y-6">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <a href="{{ route('home') }}" class="mb-4 flex justify-center">
                    <x-logo class="w-auto h-16 mx-auto text-indigo-600" />
                </a>

                <h1 class="fi-simple-header-heading text-center text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                    Sign in
                </h1>
                @if (Route::has('register'))
                    <p class="mt-2 text-sm text-center text-gray-600 leading-5 max-w">
                        Or
                        <a href="{{ route('register') }}" class="font-medium text-primary hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                            sign up for an account
                        </a>
                    </p>
                @endif
            </div>
            <form wire:submit.prevent="authenticate">
                <div class="grid gap-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700 leading-5">
                        Email address<sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
                    </label>

                    <div class="mt-1 rounded-md shadow-sm">
                        <input wire:model.lazy="email" id="email" name="email" type="email" required autofocus class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue focus:border-primary transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('email') border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:ring-red @enderror" />
                    </div>

                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 grid gap-y-2">
                    <label for="password" class="block text-sm font-medium text-gray-700 leading-5">
                        Password<sup class="text-danger-600 dark:text-danger-400 font-medium">*</sup>
                    </label>

                    <div class="mt-1 rounded-md shadow-sm">
                        <input wire:model.lazy="password" id="password" type="password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5 @error('password') border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:ring-red @enderror" />
                    </div>

                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mt-6">
                    <div class="flex items-center">
                        <input wire:model.lazy="remember" id="remember" type="checkbox" class="form-checkbox w-4 h-4 text-indigo-600 transition duration-150 ease-in-out" />
                        <label for="remember" class="block ml-2 text-sm text-gray-900 leading-5">
                            Remember
                        </label>
                    </div>

                    <div class="text-sm leading-5">
                        <a href="{{ route('password.request') }}" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                            Forgot your password?
                        </a>
                    </div>
                </div>

                <div class="mt-6">
                    <span class="block w-full rounded-md shadow-sm">
                        <button type="submit" class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-primary border border-transparent rounded-md hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
                            Sign in
                        </button>
                    </span>
                </div>
            </form>
        </section>
    </div>
</div>

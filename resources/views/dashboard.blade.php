<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __('You're logged in!') }}

                    @if(session()->has('impersonated_user_id'))
                        <div class="mt-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
                            <p class="font-bold">Impersonation Mode Active</p>
                            <p>You are currently viewing the dashboard as {{ Auth::user()->name }}.</p>
                            <a href="{{ route('impersonate.stop') }}" class="mt-2 inline-block font-semibold underline">
                                Stop Impersonating
                            </a>
                        </div>
                    @endif

                    @if(Auth::user()->hasRole('Admin') && !session()->has('impersonated_user_id'))
                        <div class="mt-4 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700">
                            <p class="font-bold">Admin Tools</p>
                            <p>You can impersonate any user from the user management panel.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

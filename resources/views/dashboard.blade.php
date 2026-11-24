<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-autumn-cream leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-autumn-cream">Mijn zendingen</h2>
                <a href="{{ route('packages.create') }}" class="bg-autumn-orange text-white px-4 py-2 rounded-none font-bold">
                    + Nieuw pakket
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-autumn-surface p-6 border-l-4 border-autumn-orange">
                    <p class="text-sm font-medium text-autumn-cream/60 uppercase">Totaal aantal pakketten</p>
                    <p class="text-3xl font-bold text-autumn-cream">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-autumn-surface p-6 border-l-4 border-autumn-green">
                    <p class="text-sm font-medium text-autumn-cream/60 uppercase">Actieve zendingen</p>
                    <p class="text-3xl font-bold text-autumn-cream">{{ $stats['active'] }}</p>
                </div>
                <div class="bg-autumn-surface p-6 border-l-4 border-autumn-orange/40">
                    <p class="text-sm font-medium text-autumn-cream/60 uppercase">Bezorgd</p>
                    <p class="text-3xl font-bold text-autumn-cream">{{ $stats['delivered'] }}</p>
                </div>
            </div>

            <div class="bg-autumn-surface overflow-hidden p-6">
                <div class="text-autumn-cream">
                    {{ __("Je bent ingelogd!") }}

                    @if(session()->has('impersonated_user_id'))
                        <div class="mt-4 p-4 bg-autumn-orange/20 border-l-4 border-autumn-orange text-autumn-cream">
                            <p class="font-bold">Impersonatie-modus actief</p>
                            <p>Je bekijkt momenteel het dashboard als {{ Auth::user()->name }}.</p>
                            <a href="{{ route('impersonate.stop') }}" class="mt-2 inline-block font-semibold underline">
                                Stop impersonatie
                            </a>
                        </div>
                    @endif

                    @if(Auth::user()->hasRole('Admin') && !session()->has('impersonated_user_id'))
                        <div class="mt-4 p-4 bg-autumn-green/20 border-l-4 border-autumn-green text-autumn-cream">
                            <p class="font-bold">Admin-tools</p>
                            <p>Je kunt elke gebruiker imiteren vanuit het gebruikersbeheerpaneel.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
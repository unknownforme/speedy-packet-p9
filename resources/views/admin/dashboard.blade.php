<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-autumn-cream leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-autumn-cream">Admin Dashboard</h2>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
                <div class="bg-autumn-surface p-4 border-l-4 border-autumn-orange shadow-lg">
                    <p class="text-xs font-medium text-autumn-cream/60 uppercase">Pakketten</p>
                    <p class="text-2xl font-bold text-autumn-cream">{{ $stats['total_packages'] }}</p>
                </div >
                <div class="bg-autumn-surface p-4 border-l-4 border-autumn-green shadow-lg">
                    <p class="text-xs font-medium text-autumn-cream/60 uppercase">Actief</p>
                    <p class="text-2xl font-bold text-autumn-cream">{{ $stats['active_packages'] }}</p>
                </div >
                <div class="bg-autumn-surface p-4 border-l-4 border-autumn-brown shadow-lg">
                    <p class="text-xs font-medium text-autumn-cream/60 uppercase">Bedrijven</p>
                    <p class="text-2xl font-bold text-autumn-cream">{{ $stats['total_companies'] }}</p>
                </div >
                <div class="bg-autumn-surface p-4 border-l-4 border-autumn-green shadow-lg">
                    <p class="text-xs font-medium text-autumn-cream/60 uppercase">Gebruikers</p>
                    <p class="text-2xl font-bold text-autumn-cream">{{ $stats['total_users'] }}</p>
                </div >
                <div class="bg-autumn-surface p-4 border-l-4 border-autumn-orange/40 shadow-lg">
                    <p class="text-xs font-medium text-autumn-cream/60 uppercase">Omzet</p>
                    <p class="text-2xl font-bold text-autumn-cream">€{{ number_format($stats['total_revenue_cents'] / 100, 2) }}</p>
                </div >
            </div>

            <!-- Quick Actions -->
            <div class="bg-autumn-surface p-6 border border-autumn-brown/20 mb-8">
                <h3 class="text-lg font-bold text-autumn-cream mb-4">Snelbeheer</h3>
                <div class="flex flex-wrap gap-4">
                    <a href="#" class="bg-autumn-orange text-white px-4 py-2 rounded-none hover:bg-autumn-brown transition duration-300">Gebruikers beheren</a>
                    <a href="#" class="bg-autumn-brown text-white px-4 py-2 rounded-none hover:bg-autumn-orange transition duration-300">Bedrijven beheren</a>
                    <a href="#" class="bg-autumn-surface border border-autumn-cream/20 text-autumn-cream px-4 py-2 rounded-none hover:bg-autumn-brown transition duration-300">Alle zendingen</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
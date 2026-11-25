<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-autumn-cream leading-tight">
            Mijn zendingen
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-autumn-cream">Mijn zendingen</h2>
                <a href="{{ route('packages.create') }}" class="bg-autumn-orange text-white px-4 py-2 rounded-none font-bold hover:bg-autumn-brown transition duration-300 shadow-md">
                    + New Package
                </a>
            </div>

            <div class="bg-autumn-surface overflow-hidden shadow-md border border-autumn-brown/20">
                <table class="min-w-full divide-y divide-autumn-brown/20">
                    <thead class="bg-autumn-surface">
                        <tr class="border-b border-autumn-brown/20">
                            <th class="px-6 py-3 text-left text-xs font-medium text-autumn-cream/60 uppercase tracking-wider">Tracking Nummer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-autumn-cream/60 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-autumn-cream/60 uppercase tracking-wider">Bestemming</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-autumn-cream/60 uppercase tracking-wider">Gemaakt</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-autumn-cream/60 uppercase tracking-wider">Acties</th>
                        </tr>
                    </thead>
                    <tbody class="bg-autumn-surface divide-y divide-autumn-brown/20">
                        @forelse($packages as $package)
                            <tr class="hover:bg-autumn-surface/50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-bold text-autumn-cream">{{ $package->tracking_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-autumn-cream/70">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-none bg-autumn-orange/20 text-autumn-orange border border-autumn-orange/30">
                                        {{ __($package->current_status) }}
                                    </span\>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-autumn-cream/70">{{ $package->destination_province }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-autumn-cream/70">{{ $package->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('packages.show', $package->id) }}" class="text-autumn-orange hover:text-autumn-cream transition duration-150">Bekijk Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-sm text-autumn-cream/50">Geen pakketten gevonden.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
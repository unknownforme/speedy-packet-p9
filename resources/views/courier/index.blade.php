<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-lg mx-auto space-y-4">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-autumn-cream">Mijn Leveringen</h1>
                <span class="bg-autumn-orange/20 text-autumn-orange text-xs font-bold px-3 py-1 rounded-none uppercase tracking-wider">
                    Vandaag
                </span>
            </div>

            @forelse($packages as $package)
                <a href="{{ route('courier.show', $package) }}" class="block bg-autumn-surface border border-autumn-brown/30 p-5 rounded-none hover:border-autumn-orange transition duration-200 group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs text-autumn-cream/50 font-mono uppercase">#{{ $package->tracking_number }}</p>
                            <h3 class="text-lg font-bold text-autumn-cream mt-1 group-hover:text-autumn-orange transition">
                                {{ $package->receiver_street }} {{ $package->receiver_house_number }}
                            </h3>
                            <p class="text-sm text-autumn-cream/70">{{ $package->receiver_city }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-2 py-1 text-xs font-semibold bg-autumn-brown/50 text-autumn-cream rounded-none">
                                {{ $package->current_status }}
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-12 bg-autumn-surface border border-dashed border-autumn-brown/50">
                    <p class="text-autumn-cream/50 italic">Geen pakketten voor levering vandaag.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-autumn-cream leading-tight">
            Pakketdetails
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-autumn-surface overflow-hidden shadow-lg border border-autumn-brown/20 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-autumn-cream">Pakketdetails</h2>
                    <a href="{{ route('packages.index') }}" class="text-autumn-cream/60 hover:text-autumn-cream transition">
                        &larr; Terug naar lijst
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Details Section -->
                    <div class="space-y-4">
                        <div class="border-b border-autumn-brown/20 pb-2">
                            <p class="text-sm text-autumn-cream/60">Tracking Nummer</p>
                            <p class="text-lg font-mono font-bold text-autumn-cream">{{ $package->tracking_number }}</p>
                        </div>
                        <div class="border-b border-autumn-brown/20 pb-2">
                            <p class="text-sm text-autumn-cream/60">Status</p>
                            <p class="text-lg font-semibold text-autumn-orange">{{ __($package->current_status) }}</p>
                        </div>
                        <div class="border-b border-autumn-brown/20 pb-2">
                            <p class="text-sm text-autumn-cream/60">Bestemming</p>
                            <p class="text-lg text-autumn-cream">{{ $package->destination_province }}</p>
                        </div>
                        <div class="border-b border-autumn-brown/20 pb-2">
                            <p class="text-sm text-autumn-cream/60">Gewicht</p>
                            <p class="text-lg text-autumn-cream">{{ $package->weight_grams }}g</p>
                        </div>
                        <div class="border-b border-autumn-brown/20 pb-2">
                            <p class="text-sm text-autumn-cream/60">Verzendkosten</p>
                            <p class="text-lg text-autumn-cream">€{{ number_format($package->shipping_cost_cents / 100, 2) }}</p>
                        </div>
                        <div class="border-b border-autumn-brown/20 pb-2">
                            <p class="text-sm text-autumn-cream/60">Gemaakt op</p>
                            <p class="text-lg text-autumn-cream">{{ $package->created_at->format('d M, Y H:i') }}</p>
                        </div>
                        <div class="border-b border-autumn-brown/20 pb-2">
                            <p class="text-sm text-autumn-cream/60">E-mail van ontvanger</p>
                            <p class="text-lg text-autumn-cream">{{ $package->receiver_email ?? 'N/A' }}</p>
                        </div>
                        <div class="border-b border-autumn-brown/20 pb-2">
                            <p class="text-sm text-autumn-cream/60">Adres van ontvanger</p>
                            <p class="text-lg text-autumn-cream whitespace-pre-wrap">
                                {{ $package->receiver_street ?? '' }} {{ $package->receiver_house_number ?? '' }}{{ $package->receiver_house_number_suffix ? ' ' . $package->receiver_house_number_suffix : '' }}
                                <br>
                                {{ $package->receiver_city ?? '' }}
                            </p>
                        </div>
                    </div>

                    <!-- QR Code Section -->
                    <div class="flex flex-col items-center justify-center bg-autumn-bg rounded-none p-6 border border-autumn-brown/30">
                        <p class="text-sm font-medium text-autumn-cream/60 mb-4">Pakket QR-code</p>
                        <div class="bg-white p-4 shadow-md border border-autumn-brown/10">
                             <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $package->tracking_number }}" alt="QR Code" class="w-32 h-32">
                        </div>
                        <p class="text-xs text-autumn-cream/40 mt-4">Scan om pakket te volgen</p>
                    </div>
                </div>

                <!-- History Section -->
                <div class="mt-12">
                    <h3 class="text-xl font-bold text-autumn-cream mb-6">Statusgeschiedenis</h3>
                    <div class="relative border-l-4 border-autumn-orange/30 ml-3 space-y-6">
                        @foreach($package->statusHistory as $history)
                            <div class="mb-10 ml-6">
                                <span class="absolute flex items-center justify-center w-6 h-6 bg-autumn-orange rounded-none -left-3 ring-8 ring-autumn-surface">
                                    <div class="w-3 h-3 bg-autumn-cream rounded-none"></div>
                                </span >
                                <h4 class="text-lg font-semibold text-autumn-cream">{{ __($history->status) }}</h4>
                                <time class="block mb-2 text-sm font-normal text-autumn-cream/60">
                                    {{ $history->created_at->format('d M, Y H:i') }}
                                </time>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-autumn-cream leading-tight">
            Pakketdetails
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-autumn-surface overflow-hidden shadow-lg border border-autumn-brown/20 p-6">
                @if(session("success"))
                    <div class="bg-autumn-orange/20 border border-autumn-orange text-autumn-cream px-4 py-3 rounded-none mb-6">
                        {{ session("success") }}
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6 print:hidden">
                    <h2 class="text-2xl font-bold text-autumn-cream">Pakketdetails</h2>
                    <a href="{{ route('packages.index') }}" class="text-autumn-cream/60 hover:text-autumn-cream transition">
                        &larr; Terug naar lijst
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Details Section -->
                    <div id="details-section" class="space-y-4">
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
                            <p class="text-lg text-autumn-cream">
                                {{ $package->receiver_street ?? '' }} {{ $package->receiver_house_number ?? '' }}{{ $package->receiver_house_number_suffix ? ' ' . $package->receiver_house_number_suffix : '' }}
                                <br>
                                {{ $package->receiver_city ?? '' }}
                            </p>
                        </div>
                    </div>

                    <!-- QR Code Section -->
                    <div id="printable-qr-section" class="flex flex-col items-center justify-center bg-autumn-bg rounded-none p-6 border border-autumn-brown/30">
                        <div class="bg-white p-4 shadow-md border border-autumn-brown/10">
                             <div class="flex justify-center items-center">
                                {!! $qrSvg !!}
                             </div >
                        </div>
                        
                        <button onclick="printQrCode()" class="mt-6 px-4 py-2 bg-autumn-orange text-white text-sm font-bold rounded-none hover:bg-autumn-brown transition">
                            Print QR Code
                        </button>
                    </div>
                </div>

                <div id="status-history-section" class="mt-12 print-hide">
                    <h3 class="text-xl font-bold text-autumn-cream mb-6">Statusgeschiedenis</h3>
                    <div class="relative border-l-4 border-autumn-orange/30 ml-3 space-y-6">
                        @foreach($package->statusHistory as $history)
                            <div class="mb-10 ml-6">
                                <span class="absolute flex items-center justify-center w-6 h-6 bg-autumn-orange rounded-none -left-3 ring-8 ring-autumn-surface">
                                    <div class="w-3 h-3 bg-autumn-cream rounded-none"></div>
                                </span>
                                <h4 class="text-lg font-semibold text-autumn-cream">{{ __($history->status) }}</h4>
                                <time class="block mb-2 text-sm font-normal text-autumn-cream/60">
                                    {{ $history->created_at->format('d M, Y H:i') }}
                                </time>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div id="return-section" class="mt-12 print-hide">
                    @if(Auth::check() && (Auth::user()->id === $package->receiver_id || Auth::user()->hasRole('Admin')) && $package->current_status === 'Geleverd')
                        <div class="bg-autumn-surface border border-autumn-orange/30 p-6 rounded-none">
                            <h2 class="text-xl font-bold text-autumn-cream mb-4">Retour Aanvragen</h2>
                            <form action="{{ route('packages.return.store', $package) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="reason" class="block text-sm font-medium text-autumn-cream mb-2">Reden voor retour:</label>
                                    <textarea id="reason" name="reason" rows="3" required class="w-full bg-autumn-bg border border-autumn-brown rounded-none text-autumn-cream focus:border-autumn-orange focus:ring-0"></textarea>
                                </div>
                                <button type="submit" class="w-full bg-autumn-orange text-white py-3 px-6 rounded-none font-bold hover:bg-autumn-brown transition shadow-md">
                                    Verzoek Indienen
                                </button>
                            </form>
                        </div>
                    @elseif($package->current_status === 'Geleverd')
                        <div class="bg-autumn-surface border border-autumn-brown/30 p-4 rounded-none text-center">
                            <p class="text-sm text-autumn-cream/60 italic">Retourverzoek is niet mogelijk voor dit pakket (mogelijk buiten de 30 dagen termijn).</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
function printQrCode() {
    const qrElement = document.getElementById('printable-qr-section');
    const qrContent = qrElement.querySelector('.flex.justify-center.items-center').innerHTML;
    
    const printWindow = window.open('', '_blank', 'width=600,height=600');
    printWindow.document.write(`
        <html>
            <head>
                <title>Print QR Code</title>
                <style>
                    @page { size: 4in 4in; margin: 0; }
                    body { 
                        margin: 0; 
                        padding: 0; 
                        display: flex; 
                        justify-content: center; 
                        align-items: center; 
                        width: 4in; 
                        height: 4in; 
                        background-color: white;
                    }
                    #qr-container { 
                        width: 4in; 
                        height: 4in; 
                        display: flex; 
                        justify-content: center; 
                        align-items: center; 
                    }
                    svg { width: 300px; height: 300px; }
                </style>
            </head>
            <body>
                <div id="qr-container">
                    ${qrContent}
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(() => { window.close(); }, 500);
                    };
                <\/script>
            </body>
        </html>
    `);
    printWindow.document.close();
}
</script>

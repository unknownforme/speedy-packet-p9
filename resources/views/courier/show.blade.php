<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Track & Trace | {{ $package->tracking_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        autumn: {
                            orange: '#a04000',
                            brown: '#2d1b18',
                            green: '#1b3022',
                            cream: '#dcd3c0',
                            bg: '#1a1210',
                            surface: '#241a17',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #1a1210; color: #dcd3c0; }
    </style>
</head>
<body class="antialiased">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-autumn-green/20 border border-autumn-green text-autumn-cream rounded-none">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-900/20 border border-red-500 text-red-200 rounded-none">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('info'))
                <div class="mb-6 p-4 bg-autumn-brown/20 border border-autumn-cream text-autumn-cream rounded-none">
                    {{ session('info') }}
                </div>
            @endif

            <!-- Back to Home -->
            <div class="mb-8">
                <a href="{{ route('courier.index') }}" class="text-autumn-cream/60 hover:text-autumn-orange transition flex items-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Terug naar overzicht
                </a>
            </div>

            <!-- Package Card -->
            <div class="bg-autumn-surface border border-autumn-brown/30 p-8 rounded-none shadow-xl">
                <div class="text-center mb-8">
                    <h1 class="text-sm uppercase tracking-widest text-autumn-cream/50 mb-2">Tracking Nummer</h1>
                    <p class="text-4xl font-bold text-autumn-orange tracking-tight">{{ $package->tracking_number }}</p>
                </div>

                <div class="space-y-6">
                    <div class="flex justify-between items-center border-b border-autumn-brown/20 pb-4">
                        <span class="text-autumn-cream/60">Huidige Status</span>
                        <span class="text-xl font-bold text-autumn-orange">{{ $package->current_status }}</span>
                    </div>

                    <div class="space-y-4">
                        <h2 class="text-lg font-bold text-autumn-cream border-l-4 border-autumn-orange pl-3">Statusgeschiedenis</h2>
                        
                        <div class="relative">
                            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-autumn-brown/30"></div>

                            <div class="space-y-8">
                                @foreach($package->statusHistory as $history)
                                    <div class="relative flex items-start">
                                        <div class="absolute left-4 -translate-x-1/2 mt-1.5 w-3 h-3 rounded-full {{ $loop->first ? 'bg-autumn-orange shadow-[0_0_10px_rgba(160,64,0,0.5)]' : 'bg-autumn-brown' }}"></div>
                                        
                                        <div class="ml-10">
                                            <p class="text-sm font-bold text-autumn-cream">{{ $history->status }}</p>
                                            <p class="text-xs text-autumn-cream/50">{{ $history->created_at->format('d M Y, H:i') }}</p>
                                            @if($history->notes)
                                                <p class="text-sm text-autumn-cream/70 mt-1 italic">{{ $history->notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Form -->
            <div class="mt-8 bg-autumn-surface border border-autumn-brown/30 p-8 rounded-none shadow-xl">
                <h2 class="text-lg font-bold text-autumn-cream mb-6 uppercase tracking-wider">Levering Voltooien</h2>
                
                <form id="delivery-form" action="{{ route('courier.update', $package->id) }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="outcome" id="hidden-outcome" value="delivered_house">
                    <input type="hidden" name="reason" id="hidden-reason" value="">
                    <input type="hidden" name="extra_info" id="hidden-extra-info" value="">

                    <div>
                        <label for="outcome-select" class="block text-sm font-bold text-autumn-cream/60 mb-2 uppercase">Leveringsmethode</label>
                        <select id="outcome-select" class="w-full bg-autumn-bg border border-autumn-brown text-autumn-cream px-4 py-3 rounded-none focus:ring-2 focus:ring-autumn-orange focus:outline-none">
                            <option value="delivered_house">Direct bij de bewoner bezorgd</option>
                            <option value="delivered_neighbour">Bij de buren bezorgd</option>
                            <option value="delivered_service_point">Bij een servicepunt bezorgd</option>
                        </select>
                    </div>

                    <!-- Dynamic Extra Info Field -->
                    <div id="extra-info-container" class="hidden">
                        <label id="extra-info-label" for="extra-info-input" class="block text-sm font-bold text-autumn-cream/60 mb-2 uppercase"></label>
                        <input type="text" id="extra-info-input" class="w-full bg-autumn-bg border border-autumn-brown text-autumn-cream px-4 py-3 rounded-none focus:ring-2 focus:ring-autumn-orange focus:outline-none">
                    </div>

                    <div class="flex flex-col gap-4 pt-4">
                        <button type="submit" id="submit-delivered" class="w-full bg-autumn-orange text-white font-bold py-4 rounded-none hover:bg-autumn-brown transition duration-300">
                            BEVESTIG LEVERING
                        </button>

                        <button type="button" onclick="confirmNotDelivered()" class="w-full border border-red-500 text-red-500 font-bold py-4 rounded-none hover:bg-red-500 hover:text-white transition duration-300">
                            NIET BEZORGD
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer Info -->
            <div class="mt-12 text-center">
                <p class="text-sm text-autumn-cream/40">
                    Speedy Packet &copy; {{ date('Y') }}. Alle gegevens zijn beveiligd en geautomatiseerd.
                </p>
            </div>
        </div>
    </div>

    <script>
        const outcomeSelect = document.getElementById('outcome-select');
        const hiddenOutcome = document.getElementById('hidden-outcome');
        const extraInfoContainer = document.getElementById('extra-info-container');
        const extraInfoLabel = document.getElementById('extra-info-label');
        const extraInfoInput = document.getElementById('extra-info-input');
        const hiddenExtraInfo = document.getElementById('hidden-extra-info');
        const hiddenReason = document.getElementById('hidden-reason');
        const form = document.getElementById('delivery-form');

        outcomeSelect.addEventListener('change', function() {
            const val = this.value;
            hiddenOutcome.value = val;
            
            extraInfoContainer.classList.add('hidden');
            extraInfoInput.value = '';
            hiddenExtraInfo.value = '';

            if (val === 'delivered_neighbour') {
                extraInfoLabel.innerText = 'Buurman nummer';
                extraInfoContainer.classList.remove('hidden');
            } else if (val === 'delivered_service_point') {
                extraInfoLabel.innerText = 'Servicepunt naam';
                extraInfoContainer.classList.remove('hidden');
            }
        });

        form.addEventListener('submit', function(e) {
            if (!extraInfoContainer.classList.contains('hidden')) {
                hiddenExtraInfo.value = extraInfoInput.value;
            }
        });

        function confirmNotDelivered() {
            if (confirm('Weet je zeker dat het pakket niet bezorgd kon worden?')) {
                const reason = prompt('Wat is de reden waarom het pakket niet bezorgd kon worden?');
                if (reason !== null) {
                    hiddenOutcome.value = 'not_delivered';
                    hiddenReason.value = reason;
                    form.submit();
                }
            }
        }
    </script>
</body>
</html>
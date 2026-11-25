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
            <!-- Back to Home -->
            <div class="mb-8">
                <a href="{{ url('/') }}" class="text-autumn-cream/60 hover:text-autumn-orange transition flex items-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Terug naar startpagina
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
                            <!-- Vertical Line -->
                            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-autumn-brown/30"></div>

                            <div class="space-y-8">
                                @foreach($package->statusHistory as $history)
                                    <div class="relative flex items-start">
                                        <!-- Dot -->
                                        <div class="absolute left-4 -translate-x-1/2 mt-1.5 w-3 h-3 rounded-full {{ $loop->first ? 'bg-autumn-orange shadow-[0_0_10px_rgba(160,64,0,0.5)]' : 'bg-autumn-brown' }}"></div>
                                        
                                        <div class="ml-10">
                                            <p class="text-sm font-bold text-autumn-cream">{{ $history->status }}</p>
                                            <p class="text-xs text-autumn-cream/50">{{ $history->created_at->format('d M Y, H:i') }}</p>
                                            @if($history->note)
                                                <p class="text-sm text-autumn-cream/70 mt-1 italic">{{ $history->note }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="mt-12 text-center">
                <p class="text-sm text-autumn-cream/40">
                    Speedy Packet &copy; {{ date('Y') }}. Alle gegevens zijn beveiligd en geautomatiseerd.
                </p>
            </div>
        </div>
    </div>
</body>
</html>

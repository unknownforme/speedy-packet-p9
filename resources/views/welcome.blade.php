<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Speedy Packet') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
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
    @endif
</head>
<body class="bg-autumn-bg text-autumn-cream font-sans antialiased">
    <!-- Navigation -->
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex justify-between items-center">
        <div class="flex items-center">
            <div class="text-3xl font-bold text-autumn-orange tracking-tight">
                Speedy<span class="text-autumn-cream">Packet</span>
            </div>
        </div>
        <div class="hidden md:flex space-x-8 items-center">
            <a href="#features" class="text-autumn-cream hover:text-autumn-orange font-medium transition duration-150">Functies</a>
            <a href="#about" class="text-autumn-cream hover:text-autumn-orange font-medium transition duration-150">Over</a>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-autumn-orange text-white px-6 py-2 rounded-none font-semibold hover:bg-autumn-brown transition duration-300 shadow-md">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-autumn-cream hover:text-autumn-orange font-medium transition duration-150">Inloggen</a>
                    <a href="{{ route('register') }}" class="bg-autumn-orange text-white px-6 py-2 rounded-none font-semibold hover:bg-autumn-brown transition duration-300 shadow-md">Aan de slag</a>
                @endauth
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32 flex flex-col lg:flex-row items-center">
            <div class="lg:w-1/2 text-center lg:text-left space-y-8">
                <h1 class="text-5xl lg:text-7xl font-extrabold text-autumn-cream leading-tight">
                    Lever met <span class="text-autumn-orange">vertrouwen.</span><br>Volg met gemak.
                </h1>
                <p class="text-lg lg:text-xl text-autumn-cream/80 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    Het meest betrouwbare pakketbeheersysteem voor moderne bedrijven. Snel, transparant en gebouwd voor de toekomst van logistiek.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-autumn-orange text-white rounded-none text-lg font-bold hover:bg-autumn-brown transition duration-300 shadow-lg text-center">
                            Begin nu met verzenden
                        </a>
                    @endif
                    <a href="#features" class="px-8 py-4 bg-autumn-surface text-autumn-cream border-2 border-autumn-cream rounded-none text-lg font-bold hover:bg-autumn-brown transition duration-300 text-center">
                        Bekijk functies
                    </a>
                </div>
            </div>
            <div class="lg:w-1/2 mt-16 lg:mt-0 flex justify-center">
                <div class="relative w-72 h-72 lg:w-96 lg:h-96 bg-autumn-orange/20 rounded-none flex items-center justify-center animate-pulse">
                    <div class="absolute inset-0 bg-autumn-orange/10 rounded-none scale-110"></div>
                    <svg class="w-48 h-48 lg:w-64 lg:h-64 text-autumn-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V4a2 2 0 00-2-2H8a2 2 0 00-2 2v9m4-4V10m0 3h.01M13 13h.01M13 13l-3-3m3 3l3-3m-3 3v-3m3 3v-3m-3 3H10"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="bg-autumn-surface py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-autumn-cream mb-16">Ontworpen voor betrouwbaarheid</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="p-8 rounded-none border border-autumn-orange/10 hover:shadow-xl transition duration-300 group">
                    <div class="w-16 h-16 bg-autumn-orange/10 rounded-none flex items-center justify-center mx-auto mb-6 group-hover:bg-autumn-orange group-hover:text-white transition duration-300">
                        <svg class="w-8 h-8 text-autumn-orange group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 2.646a2 2 0 012.828 2.828l-11.478 11.477a2 2 0 01-2.828 0L3.657 5.657a2 2 0 010-2.828l1.414-1.414a2 2 0 012.828 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-autumn-cream mb-4">Real-time tracking</h3>
                    <p class="text-autumn-cream/70 leading-relaxed">
                        Weet precies waar je pakket zich in elke fase bevindt met onze geautomatiseerde statusgeschiedenis en instant meldingen.
                    </p>
                </div>

                <div class="p-8 rounded-none border border-autumn-orange/10 hover:shadow-xl transition duration-300 group">
                    <div class="w-16 h-16 bg-autumn-orange/10 rounded-none flex items-center justify-center mx-auto mb-6 group-hover:bg-autumn-orange group-hover:text-white transition duration-300">
                        <svg class="w-8 h-8 text-autumn-orange group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-4m12 4h-4m-4 0h-4M8 10h12m-12 4h12m-12 4h12"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-autumn-cream mb-4">Naadloze integratie</h3>
                    <p class="text-autumn-cream/70 leading-relaxed">
                        Koppel pakketten moeiteloos aan gebruikers via e-mail, waardoor het beheer van ontvangers een fluitje van een cent is zonder dat er accounts nodig zijn.
                    </p>
                </div>

                <div class="p-8 rounded-none border border-autumn-orange/10 hover:shadow-xl transition duration-300 group">
                    <div class="w-16 h-16 bg-autumn-orange/10 rounded-none flex items-center justify-center mx-auto mb-6 group-hover:bg-autumn-orange group-hover:text-white transition duration-300">
                        <svg class="w-8 h-8 text-autumn-orange group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2m0 0l4-4m-4 4H7m6 0v1M21 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-autumn-cream mb-4">Slimme automatisering</h3>
                    <p class="text-autumn-cream/70 leading-relaxed">
                        Gewicht-gebaseerde classificatie en intelligente routing zorgen ervoor dat elke zending met maximale precisie wordt afgehandeld.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="bg-autumn-brown text-autumn-cream py-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-8">Vereenvoudigde logistiek</h2>
            <p class="text-xl leading-relaxed opacity-90">
                Speedy Packet is ontworpen om de complexe levenscyclus van pakketbeheer te vereenvoudigen. Van de eerste verzending tot de definitieve levering bieden wij volledige zichtbaarheid voor zowel verzenders als ontvangers, wat zorgt voor gemoedsrust bij elke transactie.
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-autumn-surface py-12 border-t border-autumn-orange/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center text-autumn-cream/60 text-sm">
            <div class="mb-4 md:mb-0">
                <span class="font-bold text-autumn-cream text-lg">Speedy<span class="text-autumn-orange">Packet</span></span>
                <p class="mt-2">&copy; {{ date('Y') }} Speedy Packet. Alle rechten voorbehouden.</p>
            </div>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-autumn-orange transition">Privacybeleid</a>
                <a href="#" class="hover:text-autumn-orange transition">Servicevoorwaarden</a>
                <a href="#" class="hover:text-autumn-orange transition">Contact opnemen</a>
            </div>
        </div>
    </footer>
</body>
</html>

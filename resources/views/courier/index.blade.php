<x-app-layout>
    <!-- Include html5-qrcode library -->
    <script src="https://unpkg.com/html5-qrcode" shall_use="true"></script>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-lg mx-auto space-y-4">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-autumn-cream">Mijn Leveringen</h1>
                <div class="flex gap-2">
                    <button id="start-scan-btn" class="bg-autumn-orange text-white px-4 py-2 rounded-none font-bold text-sm shadow-md transition hover:bg-autumn-brown">
                        Scan QR Code
                    </button>
                    <span class="bg-autumn-orange/20 text-autumn-orange text-xs font-bold px-3 py-1 rounded-none uppercase tracking-wider">
                        Vandaag
                    </span>
                </div>
            </div>

            <!-- Scanner Container (Hidden by default) -->
            <div id="scanner-container" class="hidden fixed inset-0 z-50 bg-black/90 flex flex-col items-center justify-center p-4">
                <div class="w-full max-w-md bg-autumn-surface border border-autumn-brown p-4 rounded-none">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold text-autumn-cream">Scan Pakket</h2>
                        <button id="stop-scan-btn" class="text-autumn-cream text-xl font-bold">✕</button>
                    </div>
                    <div id="reader" class="w-full overflow-hidden rounded-none"></div>
                    <p id="scan-status" class="text-sm text-autumn-cream/60 mt-4 text-center">Richt de camera op de QR-code</p>
                </div>
            </div>

            <div id="package-list" class="space-y-4">
                @forelse($packages as $package)
                    <a href="{{ route('courier.show', $package) }}" data-id="{{ $package->id }}" class="package-item block bg-autumn-surface border border-autumn-brown/30 p-5 rounded-none hover:border-autumn-orange transition duration-200 group">
                        <div class="flex justify-between items-start">
                            <div class="package-info">
                                <p class="text-xs text-autumn-cream/50 font-mono uppercase">#{{ $package->tracking_number }}</p>
                                <h3 class="text-lg font-bold text-autumn-cream mt-1 group-hover:text-autumn-orange transition">
                                    {{ $package->receiver_street }} {{ $package->receiver_house_number }}
                                </h3>
                                <p class="text-sm text-autumn-cream/70">{{ $package->receiver_city }}</p>
                            </div>
                            <div class="text-right">
                                <span class="status-badge inline-block px-2 py-1 text-xs font-semibold bg-autumn-brown/50 text-autumn-cream rounded-none">
                                    {{ $package->current_status }}
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div id="empty-msg" class="text-center py-12 bg-autumn-surface border border-dashed border-autumn-brown/50">
                        <p class="text-autumn-cream/50 italic">Geen pakketten voor levering vandaag.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        let html5QrCode;

        document.getElementById('start-scan-btn').addEventListener('click', startScanner);
        document.getElementById('stop-scan-btn').addEventListener('click', stopScanner);

        async function startScanner() {
            const container = document.getElementById('scanner-container');
            container.classList.remove('hidden');
            
            html5QrCode = new Html5Qrcode("reader");
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };

            try {
                await html5QrCode.start({
                    frameCount: 1,
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                }, config, onScanSuccess);
                
                // Wait, html5-qrcode start() takes (config, callback) or (videoSource, config, callback)
                // Correct way:
                await html5QrCode.start(
                    "", 
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    onScanSuccess
                );
            } catch (err) {
                console.error(err);
                alert("Camera starten is mislukt. Zorg dat je toestemming hebt gegeven.");
                stopScanner();
            }
        }

        function stopScanner() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    document.getElementById('scanner-container').classList.add('hidden');
                }).catch(err => console.error(err));
            } else {
                document.getElementById('scanner-container').classList.add('hidden');
            }
        }

        async function onScanSuccess(decodedText, decodedResult) {
            // Stop scanning immediately to avoid multiple hits
            await stopScanner();

            document.getElementById('scan-status').innerText = "Verwerken...";
            
            try {
                const response = await fetch("{{ route('courier.scan') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ tracking_number: decodedText })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    addPackageToList(data.package);
                } else {
                    alert(data.error || "Scan mislukt.");
                }
            } catch (error) {
                console.error(error);
                alert("Er is een fout opgetreden tijdens het verwerken.");
            } finally {
                document.getElementById('scan-status').innerText = "Richt de camera op de QR-code";
            }
        }

        function addPackageToList(pkg) {
            const list = document.getElementById('package-list');
            const emptyMsg = document.getElementById('empty-msg');
            if (emptyMsg) emptyMsg.remove();

            const item = document.createElement('a');
            item.href = `/courier/packages/${pkg.id}`; // Assuming URL structure
            // Wait, the route is /courier/{package}. So /courier/123
            item.href = `/courier/${pkg.id}`;
            item.className = "block bg-autumn-surface border border-autumn-orange p-5 rounded-none hover:border-autumn-orange transition duration-200 group";
            item.setAttribute('data-id', pkg.id);
            item.innerHTML = `
                <div class="flex justify-between items-start">
                    <div class="package-info">
                        <p class="text-xs text-autumn-cream/50 font-mono uppercase">#${pkg.tracking_number}</p>
                        <h3 class="text-lg font-bold text-autumn-cream mt-1 group-hover:text-autumn-orange transition">
                            ${pkg.receiver_street} ${pkg.receiver_house_number}
                        </h3>
                        <p class="text-sm text-autumn-cream/70">${pkg.receiver_city}</p>
                    </div>
                    <div class="text-right">
                        <span class="status-badge inline-block px-2 py-1 text-xs font-semibold bg-autumn-orange text-white rounded-none">
                            Nieuw (Gescand)
                        </span>
                    </div>
                </div>
            `;
            list.prepend(item);
        }
    </script>
</x-app-layout>

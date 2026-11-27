<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-autumn-cream leading-tight">
            Dashboard Distributiecentrum
        </h2>
    </x-slot>

    <!-- Include html5-qrcode library -->
    <script src="https://unpkg.com/html5-qrcode"></script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
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

            <!-- Quick Scan Section -->
            <div class="bg-autumn-surface border border-autumn-brown/20 p-8 shadow-lg">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-autumn-cream mb-4 uppercase tracking-wider">Scan QR Code</h3>
                    <button id="start-scan-btn" class="bg-autumn-orange text-white px-4 py-2 rounded-none font-bold text-sm shadow-md transition hover:bg-autumn-brown">
                        Open Camera Scanner
                    </button>
                </div>

                <form id="scan-form" class="flex flex-col md:flex-row gap-4">
                    @csrf
                    <input type="text" id="tracking_input" name="tracking_number" placeholder="Voer tracknummer in..." 
                           class="flex-grow bg-autumn-bg border border-autumn-brown text-autumn-cream px-4 py-3 rounded-none focus:ring-2 focus:ring-autumn-orange focus:outline-none"
                           autofocus>
                    <button type="submit" class="bg-autumn-orange text-white px-8 py-3 font-bold rounded-none hover:bg-autumn-brown transition duration-300">
                        SCAN
                    </button>
                </form>

                <div id="scan-result" class="mt-6 hidden">
                    <!-- Result Card will be injected here via JS -->
                </div>
            </div>

            <!-- Package List Section -->
            <div class="bg-autumn-surface border border-autumn-brown/20 shadow-lg">
                <div class="p-6 border-b border-autumn-brown/20 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-autumn-cream uppercase tracking-wider">Wachten op verwerken</h3>
                    <span class="text-xs text-autumn-cream/50">laat pakketten zien in 'geregistreerd', 'bij hoofdkantoor', of 'in sortering'</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-autumn-brown/20">
                        <thead class="bg-autumn-bg/50 text-autumn-cream/60 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium">Tracking</th>
                                <th class="px-6 py-3 text-left font-medium">Status</th>
                                <th class="px-6 py-3 text-left font-medium">Bestemming</th>
                                <th class="px-6 py-3 text-left font-medium">Gemaakt op</th>
                                <th class="px-6 py-3 text-left font-medium">Acties</th>
                            </tr>
                        </thead>
                        <tbody id="distribution-table-body" class="divide-y divide-autumn-brown/20">
                            @forelse($packages as $package)
                                <tr class="hover:bg-autumn-surface/50 transition duration-150" id="pkg-row-{{ $package->id }}">
                                    <td class="px-6 py-4 text-sm font-mono text-autumn-cream">{{ $package->tracking_number }}</td>
                                    <td class="px-6 py-4 text-sm text-autumn-cream/70">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-none bg-autumn-orange/20 text-autumn-orange border border-autumn-orange/30">
                                            {{ __($package->current_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-autumn-cream/70">{{ $package->destination_province }}</td>
                                    <td class="px-6 py-4 text-sm text-autumn-cream/70">{{ $package->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('packages.show', $package->id) }}" class="text-autumn-orange hover:text-autumn-cream transition">Bekijk</a>
                                    </td>
                                </tr>
                            @empty
                                <tr id="empty-row">
                                    <td colspan="5" class="px-6 py-10 text-center text-sm text-autumn-cream/50">Geen pakketten om te verwerken.</td >
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- AJAX Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let html5QrCode;

        document.getElementById('start-scan-btn').addEventListener('click', startScanner);
        document.getElementById('stop-scan-btn').addEventListener('click', stopScanner);

        async function startScanner() {
            const container = document.getElementById('scanner-container');
            container.classList.remove('hidden');
            
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                alert("Uw browser ondersteunt geen camera-toegang. Gebruik een moderne browser (Chrome/Safari) en zorg dat u HTTPS gebruikt.");
                stopScanner();
                return;
            }

            html5QrCode = new Html5Qrcode("reader");
            const config = { fps: 10, qrbox: { width: 250, height: 250 } };

            try {
                await html5QrCode.start(
                    { facingMode: "environment" }, 
                    config,
                    onScanSuccess
                );
            } catch (err) {
                console.error("Scanner Error:", err);
                if (err.name === 'NotAllowedError' || err.name === 'SecurityError') {
                    alert("Camera toegang geweigerd. Controleer uw browserinstellingen of probeer een andere browser (zoals Chrome of Safari). Zorg ook dat u HTTPS gebruikt.");
                } else {
                    alert("Camera starten is mislukt: " + err.message);
                }
                stopScanner();
            }
        }

        function stopScanner() {
            document.getElementById('scanner-container').classList.add('hidden');
            if (html5QrCode) {
                html5QrCode.stop().catch(err => console.error("Stop error:", err));
            }
        }

        async function onScanSuccess(decodedText, decodedResult) {
            await stopScanner();
            document.getElementById('scan-status').innerText = "Verwerken...";
            
            try {
                const response = await fetch("{{ route('distribution.scan') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ tracking_number: decodedText })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    updateTableEntry(data.package);
                    $('#scan-result').html(`
                        <div class="bg-autumn-surface border-2 border-autumn-orange p-6 animate-in fade-in duration-300">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h4 class="text-xl font-bold text-autumn-cream">Pakket gevonden</h4>
                                    <p class="text-sm text-autumn-cream/60">ID: ${data.package.id} | ${data.package.tracking_number}</p>
                                </div>
                                <button onclick="$('#scan-result').hide()" class="text-autumn-cream/50 hover:text-autumn-cream">&times;</button>
                            </div>
                            <div class="text-center py-4">
                                <p class="text-autumn-cream font-bold">Status bijgewerkt naar: ${data.package.current_status}</p>
                            </div>
                        </div>
                    `).removeClass('hidden').show();
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

        function updateTableEntry(pkg) {
            const row = document.getElementById(`pkg-row-${pkg.id}`);
            if (row) {
                const statusBadge = row.querySelector('span.px-2');
                if (statusBadge) {
                    statusBadge.innerText = pkg.current_status;
                }
            } else {
                const tableBody = document.getElementById('distribution-table-body');
                const emptyRow = document.getElementById('empty-row');
                if (emptyRow) emptyRow.remove();

                const newRow = document.createElement('tr');
                newRow.id = `pkg-row-${pkg.id}`;
                newRow.className = "hover:bg-autumn-surface/50 transition duration-150";
                newRow.innerHTML = `
                    <td class="px-6 py-4 text-sm font-mono text-autumn-cream">${pkg.tracking_number}</td>
                    <td class="px-6 py-4 text-sm text-autumn-cream/70">
                        <span class="px-2 py-1 text-xs font-semibold rounded-none bg-autumn-orange/20 text-autumn-orange border border-autumn-orange/30">
                            ${pkg.current_status}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-autumn-cream/70">${pkg.destination_province}</td>
                    <td class="px-6 py-4 text-sm text-autumn-cream/70">Nieuw</td>
                    <td class="px-6 py-4 text-sm">
                        <a href="/packages/show/${pkg.id}" class="text-autumn-orange hover:text-autumn-cream transition">Bekijk</a>
                    </td>
                `;
                tableBody.prepend(newRow);
            }
        }

        $(document).ready(function() {
            $('#scan-form').on('submit', function(e) {
                e.preventDefault();
                const $resultDiv = $('#scan-result');
                const $input = $('#tracking_input');
                
                $resultDiv.hide().html('');
                
                $.ajax({
                    url: "{{ route('distribution.scan') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        tracking_number: $input.val()
                    },
                    success: function(response) {
                        if (response.success) {
                            updateTableEntry(response.package);
                            $resultDiv.html(`
                                <div class="bg-autumn-surface border-2 border-autumn-orange p-6 animate-in fade-in duration-300">
                                    <div class="flex justify-between items-start mb-6">
                                        <div>
                                            <h4 class="text-xl font-bold text-autumn-cream">Pakket gevonden</h4>
                                            <p class="text-sm text-autumn-cream/60">ID: ${response.package.id} | ${response.package.tracking_number}</p>
                                        </div>
                                        <button onclick="$('#scan-result').hide()" class="text-autumn-cream/50 hover:text-autumn-cream">&times;</button>
                                    </div>
                                    <div class="text-center py-4">
                                        <p class="text-autumn-cream font-bold">Status bijgewerkt naar: ${response.package.current_status}</p>
                                    </div>
                                </div>
                            `).removeClass('hidden').show();
                            $input.val('');
                        } else {
                            alert(response.error || "Scan mislukt.");
                        }
                    },
                    error: function() {
                        alert("Pakket niet gevonden. Controleer tracknummer.");
                        $input.val('');
                    }
                });
            });
        });
    </script>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-autumn-cream leading-tight">
            Dashboard Distributiecentrum
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Quick Scan Section -->
            <div class="bg-autumn-surface border border-autumn-brown/20 p-8 shadow-lg">
                <h3 class="text-lg font-bold text-autumn-cream mb-4 uppercase tracking-wider">Scan pakket</h3>
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
                        <tbody class="divide-y divide-autumn-brown/20">
                            @forelse($packages as $package)
                                <tr class="hover:bg-autumn-surface/50 transition duration-150">
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
                                <tr>
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
                        const pkg = response.package;
                        $resultDiv.html(`
                            <div class="bg-autumn-surface border-2 border-autumn-orange p-6 animate-in fade-in duration-300">
                                <div class="flex justify-between items-start mb-6">
                                    <div>
                                        <h4 class="text-xl font-bold text-autumn-cream">Pakket gevonden</h4>
                                        <p class="text-sm text-autumn-cream/60">ID: ${pkg.id} | ${pkg.tracking_number}</p>
                                    </div>
                                    <button onclick="$('#scan-result').hide()" class="text-autumn-cream/50 hover:text-autumn-cream">&times;</button>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    <div class="bg-autumn-bg p-4 border border-autumn-brown/20">
                                        <p class="text-xs text-autumn-cream/50 uppercase">Status</p>
                                        <p class="text-lg font-bold text-autumn-orange">${pkg.current_status}</p>
                                    </div>
                                    <div class="bg-autumn-bg p-4 border border-autumn-brown/20">
                                        <p class="text-xs text-autumn-cream/50 uppercase">Bestemming</p>
                                        <p class="text-lg font-bold text-autumn-cream">${pkg.destination_province}</p>
                                    </div>
                                    <div class="bg-autumn-bg p-4 border border-autumn-brown/20">
                                        <p class="text-xs text-autumn-cream/50 uppercase">Gewicht</p>
                                        <p class="text-lg font-bold text-autumn-cream">${pkg.weight_grams}g</p>
                                    </div>
                                    <div class="bg-autumn-bg p-4 border border-autumn-brown/20">
                                        <p class="text-xs text-autumn-cream/50 uppercase">E-mail van ontvanger</p>
                                        <p class="text-lg font-bold text-autumn-cream">${pkg.receiver_email || 'N/A'}</p>
                                    </div>
                                </div>

                                <hr class="border-autumn-brown/20 my-6">

                                <form id="update-status-form" class="space-y-4">
                                    <input type="hidden" name="package_id" value="${pkg.id}">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-autumn-cream/60 uppercase mb-1">Status bijwerken</label>
                                            <select name="new_status" class="w-full bg-autumn-bg border border-autumn-brown text-autumn-cream px-3 py-2 rounded-none focus:ring-autumn-orange">
                                                <option value="At Distribution Center" ${pkg.current_status === 'Bij Distributie Centrum' ? 'selected' : ''}>Bij Distributie Centrum</option>
                                                <option value="sorteren" ${pkg.current_status === 'sorteren' ? 'selected' : ''}>sorteren</option>
                                                <option value="Ready for Courier" ${pkg.current_status === 'Ready for Courier' ? 'selected' : ''}>Ready for Courier</option>
                                                <option value="Out for Delivery" ${pkg.current_status === 'Out for Delivery' ? 'selected' : ''}>Out for Delivery</option>
                                                <option value="Delivered" ${pkg.current_status === 'Delivered' ? 'selected' : ''}>Delivered</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-autumn-cream/60 uppercase mb-1">Doelprovincie (indien sorteren)</label>
                                            <input type="text" name="destination_province" value="${pkg.destination_province}" class="w-full bg-autumn-bg border border-autumn-brown text-autumn-cream px-3 py-2 rounded-none focus:ring-autumn-orange">
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full bg-autumn-orange text-white font-bold py-3 rounded-none hover:bg-autumn-brown transition duration-300">
                                        BEVESTIG UPDATE
                                    </button>
                                </form>
                            </div>
                        `);
                        $resultDiv.removeClass('hidden').show();
                        
                        // Handle the update form submission
                        $('#update-status-form').off('submit').on('submit', function(e) {
                            e.preventDefault();
                            const $btn = $(this).find('button');
                            $btn.text('BEZIG MET BEWERKEN...').prop('disabled', true);

                            $.ajax({
                                url: "{{ route('distribution.update-status') }}",
                                method: "POST",
                                data: $(this).serialize(),
                                success: function(res) {
                                    if (res.success) {
                                        alert('Succes: ' + res.message);
                                        location.reload(); // Reload to update list and clear scan
                                    } else {
                                        alert('Fout: ' + res.message);
                                        $btn.text('BEVESTIG UPDATE').prop('disabled', false);
                                    }
                                },
                                error: function(err) {
                                    alert('Update mislukt. Controleer velden.');
                                    $btn.text('BEVESTIG UPDATE').prop('disabled', false);
                                }
                            });
                        });

                    }).fail(function() {
                        alert('Pakket niet gevonden. Controleer tracknummer.');
                        $input.val('');
                    });
                });
            });
        </script>
</x-app-layout>
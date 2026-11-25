<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-autumn-cream leading-tight">
            Nieuw pakket registreren
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-autumn-surface overflow-hidden shadow-lg border border-autumn-brown/20 p-6">
                <form action="{{ route('packages.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        
                        <!-- Company -->
                        <div>
                            <label for="company_id" class="block text-sm font-medium text-autumn-cream">Verzendende bedrijf</label>
                            <select name="company_id" id="company_id" class="mt-1 block w-full rounded-none border-autumn-brown bg-autumn-bg text-autumn-cream shadow-sm focus:border-autumn-orange focus:ring-autumn-orange" required>
                                @foreach($methods as $method)
                                    <option value="{{ $method->id }}">{{ $method->name }} (€{{ number_format($method->price_cents / 100, 2) }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Weight -->
                        <div>
                            <label for="weight_grams" class="block text-sm font-medium text-autumn-cream">Gewicht (gram)</label>
                            <input type="number" name="weight_grams" id="weight_grams" value="500" class="mt-1 block w-full rounded-none border-autumn-brown bg-autumn-bg text-autumn-cream shadow-sm focus:border-autumn-orange focus:ring-autumn-orange" required>
                        </div>

                        <!-- Shipping Method -->
                        <div>
                            <label for="shipping_method_id" class="block text-sm font-medium text-autumn-cream">Verzendmethode</label>
                            <select name="shipping_method_id" id="shipping_method_id" class="mt-1 block w-full rounded-none border-autumn-brown bg-autumn-bg text-autumn-cream shadow-sm focus:border-autumn-orange focus:ring-autumn-orange" required>
                                @foreach($methods as $method)
                                    <option value="{{ $method->id }}">{{ $method->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Destination Province -->
                        <div>
                            <label for="destination_province" class="block text-sm font-medium text-autumn-cream">Bestemmingsprovincie</label>
                            <select name="destination_province" id="destination_province" class="mt-1 block w-full rounded-none border-autumn-brown bg-autumn-bg text-autumn-cream shadow-sm focus:border-autumn-orange focus:ring-autumn-orange" required>
                                @foreach($provinces as $province)
                                    <option value="{{ $province }}">{{ $province }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Receiver Email -->
                        <div>
                            <label for="receiver_email" class="block text-sm font-medium text-autumn-cream">E-mail van ontvanger</label>
                            <input type="email" name="receiver_email" id="receiver_email" placeholder="test@example.com" class="mt-1 block w-full rounded-none border-autumn-brown bg-autumn-bg text-autumn-cream shadow-sm focus:border-autumn-orange focus:ring-autumn-orange" required>
                        </div>

                        <!-- Receiver Address Fields -->
                        <div>
                            <label for="receiver_city" class="block text-sm font-medium text-autumn-cream">Stad</label>
                            <input type="text" name="receiver_city" id="receiver_city" placeholder="Stad" class="mt-1 block w-full rounded-none border-autumn-brown bg-autumn-bg text-autumn-cream shadow-sm focus:border-autumn-orange focus:ring-autumn-orange" required>
                        </div>
                        <div>
                            <label for="receiver_street" class="block text-sm font-medium text-autumn-cream">Straatnaam</label>
                            <input type="text" name="receiver_street" id="receiver_street" placeholder="Straatnaam" class="mt-1 block w-full rounded-none border-autumn-brown bg-autumn-bg text-autumn-cream shadow-sm focus:border-autumn-orange focus:ring-autumn-orange" required>
                        </div>
                        <div class="flex gap-2">
                            <div class="flex-grow">
                                <label for="receiver_house_number" class="block text-sm font-medium text-autumn-cream">Nr.</label>
                                <input type="number" name="receiver_house_number" id="receiver_house_number" placeholder="123" class="mt-1 block w-full rounded-none border-autumn-brown bg-autumn-bg text-autumn-cream shadow-sm focus:border-autumn-orange focus:ring-autumn-orange" required>
                            </div>
                            <div class="w-1/3">
                                <label for="receiver_house_number_suffix" class="block text-sm font-medium text-autumn-cream">Toevoeging</label>
                                <input type="text" name="receiver_house_number_suffix" id="receiver_house_number_suffix" placeholder="A" class="mt-1 block w-full rounded-none border-autumn-brown bg-autumn-bg text-autumn-cream shadow-sm focus:border-autumn-orange focus:ring-autumn-orange">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit" class="bg-autumn-orange hover:bg-autumn-brown text-white font-bold py-2 px-4 rounded-none transition duration-300 shadow-md">
                            Pakket registreren
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
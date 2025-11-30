<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuevo Contrato de Alquiler') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Nuevo Contrato de Alquiler</h2>

            <form action="{{ route('app.rental-contracts.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <h3 class="text-md font-medium text-gray-900 mb-2">Información del Cliente</h3>
                        <select
                            name="customer_id"
                            required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                            <option value="">Seleccione un cliente</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} {{ $customer->surname }} ({{ $customer->dni ?? 'Sin DNI' }})
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <h3 class="text-md font-medium text-gray-900 mb-2">Información del Vehículo</h3>
                        <select
                            name="vehicle_id"
                            required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                            <option value="">Seleccione un vehículo</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" 
                                    data-daily-rate="{{ $vehicle->daily_rate }}"
                                    data-weekly-rate="{{ $vehicle->weekly_rate }}"
                                    data-monthly-rate="{{ $vehicle->monthly_rate }}"
                                    {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->plate }}) - 
                                    @if($vehicle->status == 'disponible')
                                        <span class="text-green-600">Disponible</span>
                                    @else
                                        <span class="text-red-600">No disponible</span>
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <h3 class="text-md font-medium text-gray-900 mb-2">Período del Contrato</h3>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha de Inicio *</label>
                        <input
                            type="date"
                            name="start_date"
                            value="{{ old('start_date', now()->format('Y-m-d')) }}"
                            required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha de Fin *</label>
                        <input
                            type="date"
                            name="end_date"
                            value="{{ old('end_date') }}"
                            required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <h3 class="text-md font-medium text-gray-900 mb-2">Términos del Contrato</h3>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo de Alquiler *</label>
                        <select
                            name="rental_type"
                            id="rental_type"
                            required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                            <option value="daily" {{ old('rental_type') == 'daily' ? 'selected' : '' }}>Diario</option>
                            <option value="weekly" {{ old('rental_type') == 'weekly' ? 'selected' : '' }}>Semanal</option>
                            <option value="monthly" {{ old('rental_type') == 'monthly' ? 'selected' : 'selected' }}>Mensual</option>
                        </select>
                        @error('rental_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tarifa *</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                $
                            </span>
                            <input
                                type="number"
                                name="monthly_rate"
                                id="monthly_rate"
                                value="{{ old('monthly_rate') }}"
                                step="0.01"
                                min="0"
                                required
                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                            >
                        </div>
                        @error('monthly_rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Depósito de Seguridad</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                $
                            </span>
                            <input
                                type="number"
                                name="security_deposit"
                                value="{{ old('security_deposit') }}"
                                step="0.01"
                                min="0"
                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                            >
                        </div>
                        @error('security_deposit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Términos y Condiciones</label>
                        <textarea
                            name="terms"
                            rows="4"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >{{ old('terms', '1. El vehículo debe ser devuelto en las mismas condiciones en que fue entregado.
2. No se permite el uso del vehículo fuera de los límites establecidos sin autorización previa.
3. Cualquier daño al vehículo será responsabilidad del arrendatario.
4. El combustible utilizado será por cuenta del arrendatario.
5. Se aplicarán cargos por devolución tardía según lo establecido en el contrato.') }}</textarea>
                        @error('terms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2">
                    <a href="{{ route('app.rental-contracts.index') }}"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border border-gray-300 hover:bg-gray-100">
                        Cancelar
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-emerald-600 text-white hover:bg-emerald-500"
                    >
                        Guardar Contrato
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const vehicleSelect = document.querySelector('select[name="vehicle_id"]');
            const rentalTypeSelect = document.querySelector('select[name="rental_type"]');
            const monthlyRateInput = document.querySelector('input[name="monthly_rate"]');
            
            function updateRate() {
                const selectedOption = vehicleSelect.options[vehicleSelect.selectedIndex];
                if (selectedOption && selectedOption.dataset) {
                    const rateType = rentalTypeSelect.value + '_rate';
                    const rate = selectedOption.dataset[rateType] || '0';
                    monthlyRateInput.value = parseFloat(rate).toFixed(2);
                }
            }

            vehicleSelect.addEventListener('change', updateRate);
            rentalTypeSelect.addEventListener('change', updateRate);

            // Initialize rate on page load
            updateRate();
        });
    </script>
    @endpush
</x-app-layout>

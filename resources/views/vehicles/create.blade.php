<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuevo Vehículo') }}
        </h2>
    </x-slot>
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Nuevo Vehículo</h2>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Por favor corrige los siguientes errores:
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('app.vehicles.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Placa -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Placa *</label>
                        <input
                            type="text"
                            name="plate"
                            value="{{ old('plate') }}"
                            required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                        @error('plate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Marca -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Marca *</label>
                        <input
                            type="text"
                            name="brand"
                            value="{{ old('brand') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                        @error('brand')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Modelo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Modelo *</label>
                        <input
                            type="text"
                            name="model"
                            value="{{ old('model') }}"
                            required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                        @error('model')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Año -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Año</label>
                        <input
                            type="number"
                            name="year"
                            value="{{ old('year') }}"
                            min="1900"
                            max="{{ date('Y') + 1 }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                        @error('year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha de Compra -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha de Compra</label>
                        <input
                            type="date"
                            name="purchase_date"
                            value="{{ old('purchase_date') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                        @error('purchase_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kilometraje Actual -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kilometraje Actual</label>
                        <input
                            type="number"
                            name="current_km"
                            value="{{ old('current_km') }}"
                            min="0"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                        @error('current_km')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado *</label>
                        <select
                            name="status"
                            required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                            <option value="disponible" {{ old('status') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="alquilado" {{ old('status') == 'alquilado' ? 'selected' : '' }}>Alquilado</option>
                            <option value="mantenimiento" {{ old('status') == 'mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                            <option value="inactivo" {{ old('status') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tarifa Diaria -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tarifa Diaria *</label>
                        <input
                            type="number"
                            name="daily_rate"
                            value="{{ old('daily_rate') }}"
                            required
                            min="0"
                            step="0.01"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                        @error('daily_rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- KM Incluidos por Día -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">KM Incluidos por Día</label>
                        <input
                            type="number"
                            name="km_included_per_day"
                            value="{{ old('km_included_per_day') }}"
                            min="0"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                        @error('km_included_per_day')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Precio por KM Extra -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Precio por KM Extra</label>
                        <input
                            type="number"
                            name="extra_km_price"
                            value="{{ old('extra_km_price') }}"
                            min="0"
                            step="0.01"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                        @error('extra_km_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notas -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Notas</label>
                        <textarea
                            name="notes"
                            rows="3"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-2">
                    <a href="{{ route('app.vehicles.index') }}"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border border-gray-300 hover:bg-gray-100">
                        Cancelar
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-emerald-600 text-white hover:bg-emerald-500"
                    >
                        Guardar vehículo
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
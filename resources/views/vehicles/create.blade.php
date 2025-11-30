<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuevo Vehículo') }}
        </h2>
    </x-slot>
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Nuevo Vehículo</h2>

            <form action="{{ route('app.vehicles.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Marca *</label>
                        <input
                            type="text"
                            name="brand"
                            value="{{ old('brand') }}"
                            required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                        @error('brand')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

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

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Kilometraje Actual</label>
                        <input
                            type="number"
                            name="current_km"
                            value="{{ old('current_km') }}"
                            min="0"
                            step="1"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                        @error('current_km')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

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

@extends('layouts.app')

@section('page-title', 'Editar Mantenimiento #' . $maintenance->id)

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-6">Editar Mantenimiento #{{ $maintenance->id }}</h2>
            
            <form action="{{ route('app.maintenances.update', $maintenance) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Vehículo -->
                    <div>
                        <label for="vehicle_id" class="block text-sm font-medium text-gray-700">
                            Vehículo <span class="text-red-500">*</span>
                        </label>
                        <select id="vehicle_id" name="vehicle_id" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md">
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ old('vehicle_id', $maintenance->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->plate }} - {{ $vehicle->brand }} {{ $vehicle->model }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo de mantenimiento -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">
                            Tipo de mantenimiento <span class="text-red-500">*</span>
                        </label>
                        <select id="type" name="type" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md">
                            <option value="preventivo" {{ old('type', $maintenance->type) == 'preventivo' ? 'selected' : '' }}>Preventivo</option>
                            <option value="correctivo" {{ old('type', $maintenance->type) == 'correctivo' ? 'selected' : '' }}>Correctivo</option>
                            <option value="reparacion" {{ old('type', $maintenance->type) == 'reparacion' ? 'selected' : '' }}>Reparación</option>
                            <option value="inspeccion" {{ old('type', $maintenance->type) == 'inspeccion' ? 'selected' : '' }}>Inspección</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            Estado <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md">
                            <option value="pendiente" {{ old('status', $maintenance->status) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="en_progreso" {{ old('status', $maintenance->status) == 'en_progreso' ? 'selected' : '' }}>En progreso</option>
                            <option value="completado" {{ old('status', $maintenance->status) == 'completado' ? 'selected' : '' }}>Completado</option>
                            <option value="cancelado" {{ old('status', $maintenance->status) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha programada -->
                    <div>
                        <label for="scheduled_date" class="block text-sm font-medium text-gray-700">
                            Fecha programada
                        </label>
                        <input type="date" name="scheduled_date" id="scheduled_date"
                               value="{{ old('scheduled_date', $maintenance->scheduled_date?->format('Y-m-d') ?? '') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        @error('scheduled_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fecha de finalización (solo si está completado) -->
                    <div id="completed_at_container" style="display: {{ old('status', $maintenance->status) === 'completado' ? 'block' : 'none' }};">
                        <label for="completed_at" class="block text-sm font-medium text-gray-700">
                            Fecha de finalización
                        </label>
                        <input type="date" name="completed_at" id="completed_at"
                               value="{{ old('completed_at', $maintenance->completed_at?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        @error('completed_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kilometraje actual -->
                    <div>
                        <label for="current_mileage" class="block text-sm font-medium text-gray-700">
                            Kilometraje actual
                        </label>
                        <input type="number" name="current_mileage" id="current_mileage"
                               value="{{ old('current_mileage', $maintenance->current_mileage) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        @error('current_mileage')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Costo real -->
                    <div>
                        <label for="actual_cost" class="block text-sm font-medium text-gray-700">
                            Costo real
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" name="actual_cost" id="actual_cost" step="0.01" min="0"
                                   value="{{ old('actual_cost', $maintenance->actual_cost) }}"
                                   class="focus:ring-emerald-500 focus:border-emerald-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md">
                        </div>
                        @error('actual_cost')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Descripción
                        </label>
                        <textarea id="description" name="description" rows="3"
                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">{{ old('description', $maintenance->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notas -->
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700">
                            Notas
                        </label>
                        <textarea id="notes" name="notes" rows="2"
                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">{{ old('notes', $maintenance->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nota de actualización -->
                    <div class="md:col-span-2">
                        <label for="update_note" class="block text-sm font-medium text-gray-700">
                            Nota de actualización <span class="text-gray-500">(Opcional)</span>
                        </label>
                        <input type="text" name="update_note" id="update_note"
                               placeholder="Ej: Se cambió el estado a completado"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                        <p class="mt-1 text-sm text-gray-500">Agrega una nota para el historial de cambios.</p>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('app.maintenances.show', $maintenance) }}"
                       class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        Actualizar Mantenimiento
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mostrar/ocultar campo de fecha de finalización según el estado
        const statusSelect = document.getElementById('status');
        const completedAtContainer = document.getElementById('completed_at_container');
        
        function toggleCompletedAtField() {
            if (statusSelect.value === 'completado') {
                completedAtContainer.style.display = 'block';
                // Si no hay fecha de finalización, establecer la fecha actual
                if (!document.getElementById('completed_at').value) {
                    document.getElementById('completed_at').value = new Date().toISOString().split('T')[0];
                }
            } else {
                completedAtContainer.style.display = 'none';
            }
        }
        
        statusSelect.addEventListener('change', toggleCompletedAtField);
        toggleCompletedAtField(); // Ejecutar al cargar la página
    });
</script>
@endpush
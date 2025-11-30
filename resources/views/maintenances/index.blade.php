<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mantenimientos') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Mantenimientos</h1>
        <a href="{{ route('app.maintenances.create') }}"
           class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-emerald-600 text-white hover:bg-emerald-500">
            + Nuevo Mantenimiento
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <form action="{{ route('app.maintenances.index') }}" method="GET" class="flex items-center space-x-4">
                <div class="flex-1 max-w-md">
                    <label for="vehicle_id" class="block text-sm font-medium text-gray-700">Filtrar por vehículo:</label>
                    <select id="vehicle_id" name="vehicle_id" onchange="this.form.submit()"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md">
                        <option value="">Todos los vehículos</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" {{ $vehicleId == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->plate }} - {{ $vehicle->brand }} {{ $vehicle->model }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 max-w-md">
                    <label for="type" class="block text-sm font-medium text-gray-700">Tipo de mantenimiento:</label>
                    <select id="type" name="type" onchange="this.form.submit()"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md">
                        <option value="">Todos los tipos</option>
                        <option value="preventivo" {{ $type === 'preventivo' ? 'selected' : '' }}>Preventivo</option>
                        <option value="correctivo" {{ $type === 'correctivo' ? 'selected' : '' }}>Correctivo</option>
                        <option value="reparacion" {{ $type === 'reparacion' ? 'selected' : '' }}>Reparación</option>
                        <option value="inspeccion" {{ $type === 'inspeccion' ? 'selected' : '' }}>Inspección</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Vehículo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tipo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha Programada
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Acciones</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($maintenances as $maintenance)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $maintenance->vehicle->plate }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $maintenance->vehicle->brand }} {{ $maintenance->vehicle->model }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($maintenance->type === 'preventivo')
                                        bg-blue-100 text-blue-800
                                    @elseif($maintenance->type === 'correctivo')
                                        bg-yellow-100 text-yellow-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($maintenance->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $maintenance->scheduled_date?->format('d/m/Y') ?? 'No programado' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($maintenance->status === 'completado')
                                        bg-green-100 text-green-800
                                    @elseif($maintenance->status === 'pendiente')
                                        bg-yellow-100 text-yellow-800
                                    @elseif($maintenance->status === 'en_progreso')
                                        bg-blue-100 text-blue-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $maintenance->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('app.maintenances.show', $maintenance) }}" 
                                   class="text-emerald-600 hover:text-emerald-900 mr-4">Ver</a>
                                <a href="{{ route('app.maintenances.edit', $maintenance) }}" 
                                   class="text-blue-600 hover:text-blue-900">Editar</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                No se encontraron mantenimientos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $maintenances->links() }}
        </div>
        </div>
    </div>
</x-app-layout>
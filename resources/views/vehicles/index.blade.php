<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vehículos') }}
        </h2>
    </x-slot>
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Vehículos</h1>
        <a href="{{ route('app.vehicles.create') }}" 
           class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-emerald-600 text-white hover:bg-emerald-500">
            + Nuevo vehículo
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Placa</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Marca</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Modelo</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Año</th>
                    <th class="px-4 py-2 text-right font-semibold text-gray-600">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vehicles as $vehicle)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-2">
                            <a href="{{ route('app.vehicles.show', $vehicle) }}" class="text-slate-900 font-medium hover:underline">
                                {{ $vehicle->plate }}
                            </a>
                        </td>
                        <td class="px-4 py-2">{{ $vehicle->make ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $vehicle->model ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $vehicle->year ?? '-' }}</td>
                        <td class="px-4 py-2 text-right space-x-2">
                            <a href="{{ route('app.vehicles.edit', $vehicle) }}"
                               class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md border border-gray-300 hover:bg-gray-100">
                                Editar
                            </a>
                            <form action="{{ route('app.vehicles.destroy', $vehicle) }}" method="POST" class="inline-block"
                                  onsubmit="return confirm('¿Eliminar este vehículo?');">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md border border-red-200 text-red-700 hover:bg-red-50"
                                >
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                            No hay vehículos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $vehicles->links() }}
    </div>
</x-app-layout>

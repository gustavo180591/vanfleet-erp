<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Contratos de Alquiler') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Contratos de Alquiler</h1>
        <a href="{{ route('app.rental-contracts.create') }}" 
           class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-emerald-600 text-white hover:bg-emerald-500">
            + Nuevo Contrato
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Contrato #</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Cliente</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Vehículo</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Período</th>
                    <th class="px-4 py-2 text-right font-semibold text-gray-600">Mensualidad</th>
                    <th class="px-4 py-2 text-right font-semibold text-gray-600">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contracts as $contract)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-2">#{{ $contract->id }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('app.customers.show', $contract->customer) }}" 
                               class="text-slate-900 hover:underline">
                                {{ $contract->customer->name }} {{ $contract->customer->surname }}
                            </a>
                        </td>
                        <td class="px-4 py-2">
                            <a href="{{ route('app.vehicles.show', $contract->vehicle) }}"
                               class="text-slate-900 hover:underline">
                                {{ $contract->vehicle->brand }} {{ $contract->vehicle->model }} ({{ $contract->vehicle->plate }})
                            </a>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            {{ $contract->start_date->format('d/m/Y') }} - {{ $contract->end_date->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-2 text-right">${{ number_format($contract->monthly_rate, 2) }}</td>
                        <td class="px-4 py-2 text-right space-x-2">
                            <a href="{{ route('app.rental-contracts.show', $contract) }}"
                               class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md border border-gray-300 hover:bg-gray-100">
                                Ver
                            </a>
                            <a href="{{ route('app.rental-contracts.edit', $contract) }}"
                               class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md border border-gray-300 hover:bg-gray-100">
                                Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                            No hay contratos de alquiler registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $contracts->links() }}
        </div>
    </div>
</x-app-layout>

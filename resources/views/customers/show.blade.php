<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle del Cliente') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto space-y-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold">
                        {{ $customer->name }} {{ $customer->surname }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        DNI: {{ $customer->dni ?? '-' }} · Email: {{ $customer->email ?? '-' }}
                    </p>
                </div>

                <div class="space-x-2">
                    <a href="{{ route('app.customers.edit', $customer) }}"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border border-gray-300 hover:bg-gray-100">
                        Editar
                    </a>
                    <a href="{{ route('app.customers.index') }}"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-slate-900 text-white hover:bg-slate-800">
                        Volver al listado
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="font-medium text-gray-700">Teléfono</div>
                    <div class="text-gray-900">{{ $customer->phone ?? '-' }}</div>
                </div>
                <div>
                    <div class="font-medium text-gray-700">Ciudad</div>
                    <div class="text-gray-900">{{ $customer->city ?? '-' }}</div>
                </div>
                <div class="md:col-span-2">
                    <div class="font-medium text-gray-700">Dirección</div>
                    <div class="text-gray-900">{{ $customer->address ?? '-' }}</div>
                </div>
                <div class="md:col-span-2">
                    <div class="font-medium text-gray-700">Notas</div>
                    <div class="text-gray-900 whitespace-pre-line">{{ $customer->notes ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Contratos del cliente --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-base font-semibold">Contratos de alquiler</h3>
            </div>

            @if($customer->rentalContracts->isEmpty())
                <p class="text-sm text-gray-500">Este cliente no tiene contratos registrados.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Furgoneta</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Inicio</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Fin</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($customer->rentalContracts as $contract)
                            <tr class="border-b border-gray-100">
                                <td class="px-4 py-2">
                                    {{ $contract->vehicle->plate ?? '-' }}
                                    <span class="text-gray-500 text-xs">{{ $contract->vehicle->model ?? '' }}</span>
                                </td>
                                <td class="px-4 py-2">{{ $contract->start_date?->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ $contract->end_date?->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-700">
                                        {{ $contract->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Documentos del cliente --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-base font-semibold">Documentos</h3>
            </div>

            @if($customer->documents->isEmpty())
                <p class="text-sm text-gray-500">No hay documentos asociados.</p>
            @else
                <ul class="divide-y divide-gray-200 text-sm">
                    @foreach($customer->documents as $doc)
                        <li class="flex items-center justify-between py-2">
                            <div>
                                <div class="font-medium text-gray-800">
                                    {{ $doc->original_name ?? $doc->type }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    Tipo: {{ $doc->type }} · Subido: {{ $doc->created_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            <div class="space-x-2">
                                <a href="{{ route('documents.show', $doc) }}"
                                   class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md border border-gray-300 hover:bg-gray-100">
                                    Ver / descargar
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        </div>
    </div>
</x-app-layout>

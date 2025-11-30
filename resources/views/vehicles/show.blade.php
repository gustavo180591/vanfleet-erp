@extends('layouts.app')

@section('page-title', 'Detalles del Vehículo')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold">
                        {{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})
                    </h2>
                    <p class="text-sm text-gray-500">
                        Placa: {{ $vehicle->plate }} • Estado: 
                        @if($vehicle->status == 'disponible')
                            <span class="text-green-600">Disponible</span>
                        @elseif($vehicle->status == 'alquilado')
                            <span class="text-blue-600">Alquilado</span>
                        @elseif($vehicle->status == 'mantenimiento')
                            <span class="text-yellow-600">En Mantenimiento</span>
                        @else
                            <span class="text-gray-600">Inactivo</span>
                        @endif
                    </p>
                </div>

                <div class="space-x-2">
                    <a href="{{ route('app.vehicles.edit', $vehicle) }}"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border border-gray-300 hover:bg-gray-100">
                        Editar
                    </a>
                    <a href="{{ route('app.vehicles.index') }}"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-slate-900 text-white hover:bg-slate-800">
                        Volver al listado
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="font-medium text-gray-700">Placa</div>
                    <div class="text-gray-900">{{ $vehicle->plate }}</div>
                </div>
                <div>
                    <div class="font-medium text-gray-700">Marca</div>
                    <div class="text-gray-900">{{ $vehicle->brand }}</div>
                </div>
                <div>
                    <div class="font-medium text-gray-700">Modelo</div>
                    <div class="text-gray-900">{{ $vehicle->model }}</div>
                </div>
                <div>
                    <div class="font-medium text-gray-700">Año</div>
                    <div class="text-gray-900">{{ $vehicle->year }}</div>
                </div>
                @if($vehicle->purchase_date)
                    <div>
                        <div class="font-medium text-gray-700">Fecha de Compra</div>
                        <div class="text-gray-900">{{ $vehicle->purchase_date->format('d/m/Y') }}</div>
                    </div>
                @endif
                @if($vehicle->current_km)
                    <div>
                        <div class="font-medium text-gray-700">Kilometraje Actual</div>
                        <div class="text-gray-900">{{ number_format($vehicle->current_km, 0, ',', '.') }} km</div>
                    </div>
                @endif
                <div>
                    <div class="font-medium text-gray-700">Estado</div>
                    <div class="text-gray-900">
                        @if($vehicle->status == 'disponible')
                            <span class="px-2 py-1 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                Disponible
                            </span>
                        @elseif($vehicle->status == 'alquilado')
                            <span class="px-2 py-1 text-xs font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full">
                                Alquilado
                            </span>
                        @elseif($vehicle->status == 'mantenimiento')
                            <span class="px-2 py-1 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">
                                En Mantenimiento
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold leading-5 text-gray-800 bg-gray-100 rounded-full">
                                Inactivo
                            </span>
                        @endif
                    </div>
                </div>
                @if($vehicle->notes)
                    <div class="md:col-span-2">
                        <div class="font-medium text-gray-700">Notas</div>
                        <div class="text-gray-900 whitespace-pre-line">{{ $vehicle->notes }}</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Contratos de alquiler --}}
        @if($vehicle->rentalContracts && $vehicle->rentalContracts->count() > 0)
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Contratos de Alquiler</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Cliente</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Inicio</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Fin</th>
                                <th class="px-4 py-2 text-right font-semibold text-gray-600">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicle->rentalContracts as $contract)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="px-4 py-2">
                                        <a href="{{ route('app.customers.show', $contract->customer) }}" 
                                           class="text-slate-900 hover:underline">
                                            {{ $contract->customer->name }} {{ $contract->customer->surname }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-2">{{ $contract->start_date->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2">{{ $contract->end_date->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 text-right">
                                        <a href="{{ route('app.rental-contracts.show', $contract) }}"
                                           class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md border border-gray-300 hover:bg-gray-100">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Mantenimientos --}}
        @if($vehicle->maintenances && $vehicle->maintenances->count() > 0)
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Historial de Mantenimientos</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Fecha</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Tipo</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Descripción</th>
                                <th class="px-4 py-2 text-right font-semibold text-gray-600">Costo</th>
                                <th class="px-4 py-2 text-right font-semibold text-gray-600">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicle->maintenances as $maintenance)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $maintenance->date->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2">{{ $maintenance->type }}</td>
                                    <td class="px-4 py-2">{{ $maintenance->description }}</td>
                                    <td class="px-4 py-2 text-right">{{ number_format($maintenance->cost, 2) }}</td>
                                    <td class="px-4 py-2 text-right">
                                        <a href="{{ route('app.maintenances.show', $maintenance) }}"
                                           class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md border border-gray-300 hover:bg-gray-100">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Documentos --}}
        @if($vehicle->documents && $vehicle->documents->count() > 0)
            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Documentos</h3>
                    <a href="{{ route('app.documents.create', ['vehicle_id' => $vehicle->id]) }}"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-emerald-600 text-white hover:bg-emerald-500">
                        + Subir Documento
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Tipo</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Número</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Vencimiento</th>
                                <th class="px-4 py-2 text-right font-semibold text-gray-600">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicle->documents as $document)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $document->type }}</td>
                                    <td class="px-4 py-2">{{ $document->number }}</td>
                                    <td class="px-4 py-2">
                                        @if($document->expiration_date)
                                            {{ $document->expiration_date->format('d/m/Y') }}
                                            @if($document->isExpired())
                                                <span class="ml-2 px-2 py-1 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">
                                                    Vencido
                                                </span>
                                            @elseif($document->isAboutToExpire())
                                                <span class="ml-2 px-2 py-1 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">
                                                    Por vencer
                                                </span>
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-right space-x-2">
                                        <a href="{{ route('app.documents.download', $document) }}"
                                           class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md border border-gray-300 hover:bg-gray-100">
                                            Descargar
                                        </a>
                                        <a href="{{ route('app.documents.show', $document) }}"
                                           class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md border border-gray-300 hover:bg-gray-100">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection

@extends('layouts.app')

@section('page-title', 'Mantenimiento #' . $maintenance->id)

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <!-- Encabezado -->
            <div class="px-6 py-4 bg-white border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Mantenimiento #{{ $maintenance->id }}</h1>
                        <p class="text-sm text-gray-500">
                            Creado el {{ $maintenance->created_at->format('d/m/Y') }} 
                            @if($maintenance->updated_at->gt($maintenance->created_at))
                                - Actualizado el {{ $maintenance->updated_at->format('d/m/Y') }}
                            @endif
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('app.maintenances.edit', $maintenance) }}" 
                           class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Editar
                        </a>
                        <a href="{{ route('app.maintenances.index') }}" 
                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            Volver al listado
                        </a>
                    </div>
                </div>
            </div>

            <!-- Información del mantenimiento -->
            <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-8 bg-gray-50">
                <!-- Información del vehículo -->
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-3">Vehículo</h2>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center">
                                <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">
                                    {{ $maintenance->vehicle->brand }} {{ $maintenance->vehicle->model }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ $maintenance->vehicle->plate }} • {{ $maintenance->vehicle->year }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $maintenance->vehicle->color }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalles del mantenimiento -->
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-3">Detalles</h2>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Tipo</dt>
                                <dd class="mt-1 text-sm text-gray-900">
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
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                <dd class="mt-1 text-sm text-gray-900">
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
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Fecha programada</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $maintenance->scheduled_date ? $maintenance->scheduled_date->format('d/m/Y') : 'No programado' }}
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Kilometraje</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $maintenance->current_mileage ? number_format($maintenance->current_mileage, 0, ',', '.') . ' km' : 'No especificado' }}
                                </dd>
                            </div>
                            @if($maintenance->estimated_cost)
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Costo estimado</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    ${{ number_format($maintenance->estimated_cost, 2, ',', '.') }}
                                </dd>
                            </div>
                            @endif
                            @if($maintenance->completed_at)
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Completado el</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $maintenance->completed_at->format('d/m/Y') }}
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Descripción y notas -->
            <div class="px-6 py-5 bg-white">
                @if($maintenance->description)
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Descripción</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700 whitespace-pre-line">{{ $maintenance->description }}</p>
                    </div>
                </div>
                @endif

                @if($maintenance->notes)
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Notas</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-gray-700 whitespace-pre-line">{{ $maintenance->notes }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Historial de actualizaciones (si hay) -->
            @if($maintenance->history->isNotEmpty())
            <div class="px-6 py-5 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Historial de cambios</h3>
                <div class="flow-root">
                    <ul class="-mb-8">
                        @foreach($maintenance->history as $history)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-gray-500">
                                                {{ $history->description }}
                                                <span class="font-medium text-gray-900">{{ $history->user->name }}</span>
                                            </p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                            <time datetime="{{ $history->created_at->toIso8601String() }}">
                                                {{ $history->created_at->diffForHumans() }}
                                            </time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
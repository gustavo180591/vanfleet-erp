<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <i class="fas fa-tachometer-alt mr-2"></i>{{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Vehicles -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-car text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Vehículos Totales</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalVehicles ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Rentals -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-file-contract text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Alquileres Activos</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $activeRentals ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Maintenance -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-tools text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Mantenimientos Pendientes</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $pendingMaintenance ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Revenue -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-dollar-sign text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Ingresos del Mes</p>
                                <p class="text-2xl font-semibold text-gray-900">${{ number_format($monthlyRevenue ?? 0, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Quick Actions -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-bolt text-yellow-500 mr-2"></i> Acciones Rápidas
                            </h3>
                            <div class="space-y-2">
                                <a href="{{ route('app.vehicles.create') }}" class="block p-3 bg-gray-50 hover:bg-gray-100 rounded-md transition duration-150 ease-in-out">
                                    <div class="flex items-center">
                                        <i class="fas fa-plus-circle text-blue-500 mr-2"></i>
                                        <span>Agregar Vehículo</span>
                                    </div>
                                </a>
                                <div class="p-3 bg-gray-50 rounded-md opacity-50 cursor-not-allowed">
                                    <div class="flex items-center text-gray-400">
                                        <i class="fas fa-file-contract mr-2"></i>
                                        <span>Nuevo Contrato</span>
                                        <span class="ml-2 text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Próximamente</span>
                                    </div>
                                </div>
                                <div class="p-3 bg-gray-50 rounded-md opacity-50 cursor-not-allowed">
                                    <div class="flex items-center text-gray-400">
                                        <i class="fas fa-file-invoice-dollar mr-2"></i>
                                        <span>Generar Factura</span>
                                        <span class="ml-2 text-xs bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Próximamente</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-history text-blue-500 mr-2"></i> Actividad Reciente
                            </h3>
                            <div class="space-y-4">
                                @forelse($recentActivities as $activity)
                                    <div class="flex items-start pb-3 border-b border-gray-100 last:border-0 last:pb-0">
                                        <div class="p-2 bg-blue-50 rounded-full mr-3">
                                            <i class="fas {{ $activity['icon'] }} text-blue-500"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $activity['description'] }}</p>
                                            <p class="text-xs text-gray-400 mt-1">{{ $activity['time'] }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 italic">No hay actividad reciente para mostrar.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicle Status Chart -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Estado de la Flota</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 bg-green-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-green-800">Disponibles</p>
                                    <p class="text-2xl font-bold text-green-600">{{ $availableVehicles ?? 0 }}</p>
                                </div>
                                <i class="fas fa-check-circle text-green-400 text-3xl"></i>
                            </div>
                        </div>
                        <div class="p-4 bg-yellow-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-yellow-800">En Mantenimiento</p>
                                    <p class="text-2xl font-bold text-yellow-600">{{ $inMaintenance ?? 0 }}</p>
                                </div>
                                <i class="fas fa-tools text-yellow-400 text-3xl"></i>
                            </div>
                        </div>
                        <div class="p-4 bg-red-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-red-800">No Disponibles</p>
                                    <p class="text-2xl font-bold text-red-600">{{ $unavailableVehicles ?? 0 }}</p>
                                </div>
                                <i class="fas fa-times-circle text-red-400 text-3xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .shadow-custom {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
    @endpush
</x-app-layout>

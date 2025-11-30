<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del Contrato #') . $contract->id }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold">
                        Contrato #{{ $contract->id }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        {{ $contract->start_date->format('d/m/Y') }} - {{ $contract->end_date->format('d/m/Y') }}
                    </p>
                </div>

                <div class="space-x-2">
                    <a href="{{ route('app.rental-contracts.edit', $contract) }}"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border border-gray-300 hover:bg-gray-100">
                        Editar
                    </a>
                    <a href="{{ route('app.rental-contracts.index') }}"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-slate-900 text-white hover:bg-slate-800">
                        Volver al listado
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-md font-medium text-gray-900 mb-2">Cliente</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <div class="font-medium">{{ $contract->customer->name }} {{ $contract->customer->surname }}</div>
                        <div class="text-sm text-gray-600">{{ $contract->customer->dni ?? 'Sin DNI' }}</div>
                        <div class="text-sm text-gray-600">{{ $contract->customer->phone ?? 'Sin teléfono' }}</div>
                        <div class="text-sm text-gray-600">{{ $contract->customer->email ?? 'Sin correo' }}</div>
                    </div>
                </div>

                <div>
                    <h3 class="text-md font-medium text-gray-900 mb-2">Vehículo</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <div class="font-medium">{{ $contract->vehicle->brand }} {{ $contract->vehicle->model }}</div>
                        <div class="text-sm text-gray-600">Placa: {{ $contract->vehicle->plate }}</div>
                        <div class="text-sm text-gray-600">Año: {{ $contract->vehicle->year }}</div>
                        <div class="text-sm text-gray-600">Color: {{ $contract->vehicle->color ?? 'N/A' }}</div>
                    </div>
                </div>

                <div>
                    <h3 class="text-md font-medium text-gray-900 mb-2">Detalles del Contrato</h3>
                    <div class="bg-gray-50 p-4 rounded-md space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tipo de Alquiler:</span>
                            <span class="font-medium">
                                @if($contract->rental_type == 'daily')
                                    Diario
                                @elseif($contract->rental_type == 'weekly')
                                    Semanal
                                @else
                                    Mensual
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tarifa:</span>
                            <span class="font-medium">${{ number_format($contract->monthly_rate, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Depósito de Seguridad:</span>
                            <span class="font-medium">${{ number_format($contract->security_deposit, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Estado:</span>
                            <span class="font-medium">
                                @if($contract->status == 'active')
                                    <span class="text-green-600">Activo</span>
                                @elseif($contract->status == 'completed')
                                    <span class="text-blue-600">Completado</span>
                                @else
                                    <span class="text-gray-600">Cancelado</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-md font-medium text-gray-900 mb-2">Período</h3>
                    <div class="bg-gray-50 p-4 rounded-md space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fecha de Inicio:</span>
                            <span class="font-medium">{{ $contract->start_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fecha de Fin:</span>
                            <span class="font-medium">{{ $contract->end_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Duración:</span>
                            <span class="font-medium">{{ $contract->start_date->diffInDays($contract->end_date) }} días</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($contract->terms)
                <div class="mt-6">
                    <h3 class="text-md font-medium text-gray-900 mb-2">Términos y Condiciones</h3>
                    <div class="bg-gray-50 p-4 rounded-md whitespace-pre-line text-sm text-gray-700">
                        {{ $contract->terms }}
                    </div>
                </div>
            @endif
        </div>

        @if($contract->payments && $contract->payments->count() > 0)
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Pagos Registrados</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Fecha</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Monto</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Método</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Referencia</th>
                                <th class="px-4 py-2 text-left font-semibold text-gray-600">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contract->payments as $payment)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $payment->payment_date->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2">${{ number_format($payment->amount, 2) }}</td>
                                    <td class="px-4 py-2">{{ ucfirst($payment->payment_method) }}</td>
                                    <td class="px-4 py-2">{{ $payment->reference ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">
                                        @if($payment->status == 'completed')
                                            <span class="px-2 py-1 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                                Completado
                                            </span>
                                        @elseif($payment->status == 'pending')
                                            <span class="px-2 py-1 text-xs font-semibold leading-5 text-yellow-800 bg-yellow-100 rounded-full">
                                                Pendiente
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold leading-5 text-red-800 bg-red-100 rounded-full">
                                                Rechazado
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        </div>
    </div>
</x-app-layout>

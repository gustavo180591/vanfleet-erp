@extends('layouts.app')

@section('page-title', 'Factura #' . $invoice->invoice_number)

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <!-- Encabezado -->
            <div class="px-6 py-4 bg-white border-b border-gray-200">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Factura #{{ $invoice->invoice_number }}</h1>
                        <p class="text-sm text-gray-500">Emitida el {{ $invoice->issue_date->format('d/m/Y') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($invoice->status === 'paid')
                                bg-green-100 text-green-800
                            @elseif($invoice->status === 'sent')
                                bg-blue-100 text-blue-800
                            @elseif($invoice->status === 'overdue')
                                bg-red-100 text-red-800
                            @elseif($invoice->status === 'cancelled')
                                bg-gray-100 text-gray-800
                            @else
                                bg-yellow-100 text-yellow-800
                            @endif">
                            @if($invoice->status === 'paid')
                                Pagada
                            @elseif($invoice->status === 'sent')
                                Enviada
                            @elseif($invoice->status === 'overdue')
                                Vencida
                            @elseif($invoice->status === 'cancelled')
                                Cancelada
                            @else
                                Borrador
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Información de la factura y del cliente -->
            <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-8 bg-gray-50">
                <div>
                    <h2 class="text-lg font-medium text-gray-900 mb-3">Emitida a</h2>
                    <address class="not-italic">
                        <div class="font-medium">{{ $invoice->customer->name }} {{ $invoice->customer->surname }}</div>
                        @if($invoice->customer->dni)
                            <div>DNI: {{ $invoice->customer->dni }}</div>
                        @endif
                        @if($invoice->customer->phone)
                            <div>Teléfono: {{ $invoice->customer->phone }}</div>
                        @endif
                        @if($invoice->customer->email)
                            <div>Email: {{ $invoice->customer->email }}</div>
                        @endif
                        @if($invoice->customer->address)
                            <div>{{ $invoice->customer->address }}</div>
                            @if($invoice->customer->city)
                                <div>{{ $invoice->customer->city }}</div>
                            @endif
                        @endif
                    </address>
                </div>
                <div class="md:text-right">
                    <h2 class="text-lg font-medium text-gray-900 mb-3">Detalles de la Factura</h2>
                    <div class="space-y-1">
                        <div><span class="text-gray-600">Número:</span> {{ $invoice->invoice_number }}</div>
                        <div><span class="text-gray-600">Fecha de Emisión:</span> {{ $invoice->issue_date->format('d/m/Y') }}</div>
                        <div><span class="text-gray-600">Fecha de Vencimiento:</span> {{ $invoice->due_date->format('d/m/Y') }}</div>
                        @if($invoice->rentalContract)
                            <div class="mt-2">
                                <span class="text-gray-600">Contrato:</span> 
                                <a href="{{ route('app.rental-contracts.show', $invoice->rentalContract) }}" 
                                   class="text-emerald-600 hover:text-emerald-900">
                                    #{{ $invoice->rentalContract->id }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Líneas de factura -->
            <div class="px-6 py-5">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Descripción
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cantidad
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Precio Unitario
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($invoice->items as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->description }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                        {{ number_format($item->quantity, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                        ${{ number_format($item->unit_price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                                        ${{ number_format($item->total, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Totales -->
                <div class="mt-8 flex justify-end">
                    <div class="w-1/3">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm text-gray-700">
                                <span>Subtotal:</span>
                                <span>${{ number_format($invoice->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-700">
                                <span>IVA (21%):</span>
                                <span>${{ number_format($invoice->tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-semibold text-gray-900 border-t border-gray-200 pt-2">
                                <span>Total:</span>
                                <span>${{ number_format($invoice->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($invoice->notes)
                    <div class="mt-8 pt-4 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-gray-900">Notas</h3>
                        <div class="mt-1 text-sm text-gray-700 whitespace-pre-line">{{ $invoice->notes }}</div>
                    </div>
                @endif
            </div>

            <!-- Acciones -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                <div class="flex space-x-3">
                    <a href="{{ route('app.invoices.download', $invoice) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Descargar PDF
                    </a>
                    
                    <button type="button" 
                            onclick="sendInvoiceEmail('{{ $invoice->customer->email }}')"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Enviar por Email
                    </button>
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('app.invoices.edit', $invoice) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                    </a>
                    
                    <a href="{{ route('app.invoices.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        Volver al listado
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para enviar email -->
    <div id="emailModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Enviar factura por correo electrónico
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                La factura se enviará a la siguiente dirección de correo electrónico:
                            </p>
                            <p id="recipientEmail" class="mt-1 text-sm font-medium text-gray-900"></p>
                        </div>
                        <div class="mt-4">
                            <label for="additionalMessage" class="block text-sm font-medium text-gray-700 text-left">
                                Mensaje adicional (opcional)
                            </label>
                            <div class="mt-1">
                                <textarea id="additionalMessage" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Escribe un mensaje opcional..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="button" onclick="confirmSendEmail()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:col-start-2 sm:text-sm">
                        Enviar
                    </button>
                    <button type="button" onclick="closeEmailModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function sendInvoiceEmail(defaultEmail) {
        document.getElementById('recipientEmail').textContent = defaultEmail;
        document.getElementById('emailModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeEmailModal() {
        document.getElementById('emailModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function confirmSendEmail() {
        const email = document.getElementById('recipientEmail').textContent;
        const message = document.getElementById('additionalMessage').value;
        
        // Aquí iría la lógica para enviar el correo
        fetch('{{ route('app.invoices.send-email', $invoice) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                email: email,
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            closeEmailModal();
            alert('Factura enviada correctamente');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al enviar la factura');
        });
    }
</script>
@endpush

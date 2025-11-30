@extends('layouts.app')

@section('page-title', 'Editar Factura #' . $invoice->invoice_number)

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-6">Editar Factura #{{ $invoice->invoice_number }}</h2>

            <form action="{{ route('app.invoices.update', $invoice) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Información del Cliente -->
                    <div class="md:col-span-2">
                        <h3 class="text-md font-medium text-gray-900 mb-3">Información del Cliente</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                                <select id="customer_id" name="customer_id" 
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md">
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" 
                                                {{ old('customer_id', $invoice->customer_id) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} {{ $customer->surname }} ({{ $customer->dni ?? 'Sin DNI' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="rental_contract_id" class="block text-sm font-medium text-gray-700">Contrato de Alquiler</label>
                                <select id="rental_contract_id" name="rental_contract_id" 
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md">
                                    <option value="">Sin contrato asociado</option>
                                    @foreach($rentalContracts as $contract)
                                        <option value="{{ $contract->id }}" 
                                                {{ old('rental_contract_id', $invoice->rental_contract_id) == $contract->id ? 'selected' : '' }}
                                                data-customer-id="{{ $contract->customer_id }}">
                                            #{{ $contract->id }} - {{ $contract->vehicle->brand }} {{ $contract->vehicle->model }} ({{ $contract->vehicle->plate }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('rental_contract_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Fechas -->
                    <div class="md:col-span-2">
                        <h3 class="text-md font-medium text-gray-900 mb-3">Fechas</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="issue_date" class="block text-sm font-medium text-gray-700">Fecha de Emisión</label>
                                <input type="date" name="issue_date" id="issue_date" 
                                       value="{{ old('issue_date', $invoice->issue_date->format('Y-m-d')) }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                @error('issue_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700">Fecha de Vencimiento</label>
                                <input type="date" name="due_date" id="due_date" 
                                       value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                @error('due_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                                <select id="status" name="status" 
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md">
                                    <option value="draft" {{ old('status', $invoice->status) == 'draft' ? 'selected' : '' }}>Borrador</option>
                                    <option value="sent" {{ old('status', $invoice->status) == 'sent' ? 'selected' : '' }}>Enviada</option>
                                    <option value="paid" {{ old('status', $invoice->status) == 'paid' ? 'selected' : '' }}>Pagada</option>
                                    <option value="overdue" {{ old('status', $invoice->status) == 'overdue' ? 'selected' : '' }}>Vencida</option>
                                    <option value="cancelled" {{ old('status', $invoice->status) == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Líneas de Factura -->
                    <div class="md:col-span-2">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-md font-medium text-gray-900">Líneas de Factura</h3>
                            <button type="button" id="add-item" 
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                + Agregar Línea
                            </button>
                        </div>

                        <div id="items-container" class="space-y-4">
                            @foreach(old('items', $invoice->items) as $index => $item)
                                <div class="grid grid-cols-12 gap-4 items-end" data-item>
                                    <div class="col-span-6">
                                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                                        <input type="text" name="items[{{ $index }}][description]" 
                                               value="{{ old("items.$index.description", $item->description) }}"
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                                        <input type="number" name="items[{{ $index }}][quantity]" step="0.01" min="0.01" 
                                               value="{{ old("items.$index.quantity", $item->quantity) }}"
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Precio Unitario</label>
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                                $
                                            </span>
                                            <input type="number" name="items[{{ $index }}][unit_price]" step="0.01" min="0" 
                                                   value="{{ old("items.$index.unit_price", $item->unit_price) }}"
                                                   class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">
                                        </div>
                                    </div>
                                    <div class="col-span-1 flex items-end">
                                        <button type="button" class="remove-item text-red-600 hover:text-red-900">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Notas -->
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notas</label>
                        <textarea id="notes" name="notes" rows="3" 
                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm">{{ old('notes', $invoice->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Totales -->
                <div class="mt-8 border-t border-gray-200 pt-5">
                    <div class="flex justify-end">
                        <div class="w-1/3">
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm text-gray-700">
                                    <span>Subtotal:</span>
                                    <span id="subtotal">${{ number_format($invoice->subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-700">
                                    <span>IVA (21%):</span>
                                    <span id="tax">${{ number_format($invoice->tax, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-lg font-semibold text-gray-900 border-t border-gray-200 pt-2">
                                    <span>Total:</span>
                                    <span id="total">${{ number_format($invoice->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('app.invoices.show', $invoice) }}" 
                       class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                        Actualizar Factura
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Contador para los índices de los items
        let itemIndex = {{ count(old('items', $invoice->items)) }};

        // Agregar nueva línea de factura
        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const newItem = container.firstElementChild.cloneNode(true);
            
            // Actualizar los nombres de los campos con el nuevo índice
            const newItemHtml = newItem.outerHTML.replace(/items\[\d+\]/g, `items[${itemIndex}]`);
            container.insertAdjacentHTML('beforeend', newItemHtml);
            
            // Incrementar el contador
            itemIndex++;
            
            // Agregar manejador de eventos al botón de eliminar
            const removeButtons = document.querySelectorAll('.remove-item');
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (document.querySelectorAll('[data-item]').length > 1) {
                        this.closest('[data-item]').remove();
                        calculateTotals();
                    }
                });
            });
            
            // Agregar manejadores de eventos para los inputs de cantidad y precio
            const newItemElement = container.lastElementChild;
            const quantityInput = newItemElement.querySelector('input[name$="[quantity]"]');
            const priceInput = newItemElement.querySelector('input[name$="[unit_price]"]');
            
            if (quantityInput && priceInput) {
                quantityInput.addEventListener('input', calculateTotals);
                priceInput.addEventListener('input', calculateTotals);
            }
        });

        // Eliminar línea de factura
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
                const button = e.target.classList.contains('remove-item') ? e.target : e.target.closest('.remove-item');
                if (document.querySelectorAll('[data-item]').length > 1) {
                    button.closest('[data-item]').remove();
                    calculateTotals();
                }
            }
        });

        // Calcular totales cuando cambia la cantidad o el precio
        document.addEventListener('input', function(e) {
            if (e.target.matches('input[name$="[quantity]"], input[name$="[unit_price]"]')) {
                calculateTotals();
            }
        });

        // Función para calcular los totales
        function calculateTotals() {
            let subtotal = 0;
            
            // Calcular subtotal sumando todas las líneas
            document.querySelectorAll('[data-item]').forEach(item => {
                const quantity = parseFloat(item.querySelector('input[name$="[quantity]"]').value) || 0;
                const unitPrice = parseFloat(item.querySelector('input[name$="[unit_price]"]').value) || 0;
                const total = quantity * unitPrice;
                subtotal += total;
            });
            
            // Calcular IVA (21%)
            const tax = subtotal * 0.21;
            const total = subtotal + tax;
            
            // Actualizar la interfaz
            document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById('tax').textContent = `$${tax.toFixed(2)}`;
            document.getElementById('total').textContent = `$${total.toFixed(2)}`;
        }

        // Filtrar contratos según el cliente seleccionado
        const customerSelect = document.getElementById('customer_id');
        const contractSelect = document.getElementById('rental_contract_id');
        
        if (customerSelect && contractSelect) {
            customerSelect.addEventListener('change', function() {
                const customerId = this.value;
                const options = contractSelect.querySelectorAll('option');
                
                options.forEach(option => {
                    if (option.value === '') {
                        option.style.display = 'block';
                    } else {
                        if (option.dataset.customerId === customerId) {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    }
                });
                
                // Resetear el valor seleccionado si el contrato no pertenece al cliente
                if (contractSelect.value && contractSelect.querySelector(`option[value="${contractSelect.value}"]`).style.display === 'none') {
                    contractSelect.value = '';
                }
            });
            
            // Disparar el evento change al cargar la página si ya hay un cliente seleccionado
            if (customerSelect.value) {
                customerSelect.dispatchEvent(new Event('change'));
            }
        }

        // Calcular totales al cargar la página
        calculateTotals();
    });
</script>
@endpush

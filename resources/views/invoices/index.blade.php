<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Facturas') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Facturas</h1>
        <a href="{{ route('app.invoices.create') }}" 
           class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-emerald-600 text-white hover:bg-emerald-500">
            + Nueva Factura
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <form action="{{ route('app.invoices.index') }}" method="GET" class="flex items-center space-x-4">
                <div class="flex-1 max-w-md">
                    <label for="status" class="block text-sm font-medium text-gray-700">Filtrar por estado:</label>
                    <select id="status" name="status" onchange="this.form.submit()" 
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm rounded-md">
                        <option value="">Todos</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Borrador</option>
                        <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Enviada</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Pagada</option>
                        <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Vencida</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Factura #
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cliente
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Vencimiento
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total
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
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $invoice->invoice_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $invoice->customer->name }} {{ $invoice->customer->surname }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $invoice->issue_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $invoice->due_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                ${{ number_format($invoice->total, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($invoice->status === 'paid')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Pagada
                                    </span>
                                @elseif($invoice->status === 'sent')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Enviada
                                    </span>
                                @elseif($invoice->status === 'overdue')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Vencida
                                    </span>
                                @elseif($invoice->status === 'cancelled')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Cancelada
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Borrador
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('app.invoices.show', $invoice) }}" 
                                       class="text-emerald-600 hover:text-emerald-900">
                                        Ver
                                    </a>
                                    <a href="{{ route('app.invoices.download', $invoice) }}" 
                                       class="text-blue-600 hover:text-blue-900 ml-4">
                                        PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                No se encontraron facturas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $invoices->links() }}
        </div>
        </div>
    </div>
</x-app-layout>

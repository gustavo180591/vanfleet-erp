<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Document Management') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Document Management</h1>
        <a href="{{ route('app.documents.create') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-emerald-600 text-white hover:bg-emerald-500">
            Upload New Document
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form action="{{ route('app.documents.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer</label>
                <select name="customer_id" id="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Customers</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="contract_id" class="block text-sm font-medium text-gray-700">Contract</label>
                <select name="contract_id" id="contract_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Contracts</option>
                    @foreach($contracts as $contract)
                        <option value="{{ $contract->id }}" {{ request('contract_id') == $contract->id ? 'selected' : '' }}>
                            {{ $contract->contract_number }} - {{ $contract->customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Document Type</label>
                <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Types</option>
                    <option value="contract" {{ request('type') == 'contract' ? 'selected' : '' }}>Contract</option>
                    <option value="invoice" {{ request('type') == 'invoice' ? 'selected' : '' }}>Invoice</option>
                    <option value="receipt" {{ request('type') == 'receipt' ? 'selected' : '' }}>Receipt</option>
                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="inline-flex justify-center items-center px-3 py-2 text-sm font-medium rounded-md bg-emerald-600 text-white hover:bg-emerald-500 w-full">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Documents Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contract</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($documents as $document)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-emerald-100 rounded-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('app.documents.download', $document) }}" class="text-blue-600 hover:text-blue-800">
                                                {{ $document->original_filename }}
                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ number_format($document->size / 1024, 2) }} KB
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $typeColors = [
                                        'contract' => 'bg-emerald-100 text-emerald-800',
                                        'invoice' => 'bg-emerald-100 text-emerald-800',
                                        'receipt' => 'bg-purple-100 text-purple-800',
                                        'other' => 'bg-gray-100 text-gray-800'
                                    ][$document->type] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $typeColors }}">
                                    {{ ucfirst($document->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $document->customer ? $document->customer->name : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $document->contract ? $document->contract->contract_number : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $document->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('app.documents.download', $document) }}" class="text-blue-600 hover:text-blue-900 mr-3" title="Download">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('app.documents.edit', $document) }}" class="text-yellow-600 hover:text-yellow-900 mr-3" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('app.documents.destroy', $document) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this document?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No documents found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($documents->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $documents->withQueryString()->links() }}
            </div>
        @endif
            </div>
        </div>
    </div>

    @push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter contracts based on selected customer
        const customerSelect = document.getElementById('customer_id');
        const contractSelect = document.getElementById('contract_id');
        
        if (customerSelect && contractSelect) {
            customerSelect.addEventListener('change', function() {
                const customerId = this.value;
                const currentContractId = contractSelect.value;
                
                // Reset contracts
                contractSelect.innerHTML = '<option value="">All Contracts</option>';
                
                if (!customerId) {
                    // If no customer selected, load all contracts
                    fetch(`{{ route('app.rental-contracts.index') }}?json=1&customer_id=${customerId || ''}`)
                        .then(response => response.json())
                        .then(contracts => {
                            contracts.forEach(contract => {
                                const option = new Option(
                                    `${contract.contract_number} - ${contract.customer_name}`,
                                    contract.id,
                                    false,
                                    contract.id == currentContractId
                                );
                                contractSelect.add(option);
                            });
                        });
                    return;
                }
                
                // Load contracts for selected customer
                fetch(`{{ route('app.rental-contracts.index') }}?json=1&customer_id=${customerId}`)
                    .then(response => response.json())
                    .then(contracts => {
                        contracts.forEach(contract => {
                            const option = new Option(
                                `${contract.contract_number} - ${contract.customer_name}`,
                                contract.id,
                                false,
                                contract.id == currentContractId
                            );
                            contractSelect.add(option);
                        });
                    });
            });
            
            // Trigger change event if customer is already selected
            if (customerSelect.value) {
                customerSelect.dispatchEvent(new Event('change'));
            }
        }
    });
</script>
    @endpush
</x-app-layout>

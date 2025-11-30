<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Document') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Edit Document</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $document->original_filename }}</p>
            </div>
            
            <form action="{{ route('app.documents.update', $document) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6">
                    <!-- Document Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Document Type</label>
                        <select id="type" name="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" required>
                            <option value="contract" {{ old('type', $document->type) == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="invoice" {{ old('type', $document->type) == 'invoice' ? 'selected' : '' }}>Invoice</option>
                            <option value="receipt" {{ old('type', $document->type) == 'receipt' ? 'selected' : '' }}>Receipt</option>
                            <option value="other" {{ old('type', $document->type) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Customer Selection -->
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer (Optional)</label>
                        <select id="customer_id" name="customer_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Select a customer (optional)</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id', $document->customer_id) == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Contract Selection -->
                    <div>
                        <label for="contract_id" class="block text-sm font-medium text-gray-700">Contract (Optional)</label>
                        <select id="contract_id" name="contract_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Select a contract (optional)</option>
                            @foreach($contracts as $contract)
                                <option value="{{ $contract->id }}" 
                                    data-customer-id="{{ $contract->customer_id }}" 
                                    {{ old('contract_id', $document->contract_id) == $contract->id ? 'selected' : '' }}>
                                    {{ $contract->contract_number }} - {{ $contract->customer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('contract_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                        <div class="mt-1">
                            <textarea id="description" name="description" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('description', $document->description) }}</textarea>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">A brief description of the document.</p>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- File Info -->
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Current File</h3>
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-emerald-100 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('app.documents.download', $document) }}" class="text-emerald-600 hover:text-emerald-800">
                                        {{ $document->original_filename }}
                                    </a>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ number_format($document->size / 1024, 2) }} KB • {{ $document->mime_type }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    Uploaded: {{ $document->created_at->format('M d, Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Replace File -->
                    <div class="border-t border-gray-200 pt-4">
                        <label for="document" class="block text-sm font-medium text-gray-700 mb-2">Replace File (Optional)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="document" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                        <span>Upload a new file</span>
                                        <input id="document" name="document" type="file" class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    Leave empty to keep current file
                                </p>
                            </div>
                        </div>
                        @error('document')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-6 flex items-center justify-between">
                    <div>
                        <button type="button" onclick="if(confirm('Are you sure you want to delete this document?')) { document.getElementById('delete-form').submit(); }" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete Document
                        </button>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('app.documents.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-emerald-600 text-white hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                            Update Document
                        </button>
                    </div>
                </div>
            </form>
            
            <!-- Delete Form -->
            <form id="delete-form" action="{{ route('app.documents.destroy', $document) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
            </div>
        </div>
    </div>

    @push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const customerSelect = document.getElementById('customer_id');
        const contractSelect = document.getElementById('contract_id');
        const contractOptions = Array.from(contractSelect.options);
        
        // Filter contracts based on selected customer
        function filterContracts() {
            const customerId = customerSelect.value;
            
            // Reset contract select
            contractSelect.innerHTML = '<option value="">Select a contract (optional)</option>';
            
            // Filter and add contracts that match the selected customer
            contractOptions.forEach(option => {
                if (!option.value || (option.dataset.customerId === customerId)) {
                    contractSelect.add(option);
                }
            });
            
            // If a contract was previously selected but is no longer valid, clear it
            if (contractSelect.value && contractSelect.selectedIndex === -1) {
                contractSelect.value = '';
            }
        }
        
        // Initial filter
        filterContracts();
        
        // Add event listener for customer selection change
        if (customerSelect) {
            customerSelect.addEventListener('change', filterContracts);
        }
    });
</script>
    @endpush
</x-app-layout>
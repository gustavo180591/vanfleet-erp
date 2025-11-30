<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload New Document') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Upload New Document</h2>
            </div>
            
            <form action="{{ route('app.documents.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 gap-6">
                    <!-- Document File -->
                    <div>
                        <label for="document" class="block text-sm font-medium text-gray-700">Document File</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="document" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                        <span>Upload a file</span>
                                        <input id="document" name="document" type="file" class="sr-only" required>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PDF, DOC, DOCX, XLS, XLSX, JPG, PNG up to 10MB
                                </p>
                            </div>
                        </div>
                        @error('document')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Document Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Document Type</label>
                        <select id="type" name="type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" required>
                            <option value="" disabled selected>Select document type</option>
                            <option value="contract" {{ old('type') == 'contract' ? 'selected' : '' }}>Contract</option>
                            <option value="invoice" {{ old('type') == 'invoice' ? 'selected' : '' }}>Invoice</option>
                            <option value="receipt" {{ old('type') == 'receipt' ? 'selected' : '' }}>Receipt</option>
                            <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
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
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
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
                                <option value="{{ $contract->id }}" data-customer-id="{{ $contract->customer_id }}" {{ old('contract_id') == $contract->id ? 'selected' : '' }}>
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
                            <textarea id="description" name="description" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('description') }}</textarea>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">A brief description of the document.</p>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-6 flex items-center justify-end space-x-4">
                    <a href="{{ route('app.documents.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Upload Document
                    </button>
                </div>
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

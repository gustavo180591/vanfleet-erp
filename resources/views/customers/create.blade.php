<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuevo Cliente') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Crear cliente</h2>

            <form action="{{ route('app.customers.store') }}" method="POST" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre *</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Apellido</label>
                        <input
                            type="text"
                            name="surname"
                            value="{{ old('surname') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">DNI</label>
                        <input
                            type="text"
                            name="dni"
                            value="{{ old('dni') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ciudad</label>
                        <input
                            type="text"
                            name="city"
                            value="{{ old('city') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input
                        type="text"
                        name="address"
                        value="{{ old('address') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Notas</label>
                    <textarea
                        name="notes"
                        rows="3"
                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                    >{{ old('notes') }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('app.customers.index') }}"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md border border-gray-300 hover:bg-gray-100">
                        Cancelar
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-emerald-600 text-white hover:bg-emerald-500"
                    >
                        Guardar cliente
                    </button>
                </div>
            </form>
        </div>
        </div>
    </div>
</x-app-layout>

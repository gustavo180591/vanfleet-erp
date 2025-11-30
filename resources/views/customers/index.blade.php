<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Clientes') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-4">
        <form action="{{ route('app.customers.index') }}" method="GET" class="flex items-center gap-2">
            <input
                type="text"
                name="q"
                value="{{ $search }}"
                placeholder="Buscar por nombre, DNI o email"
                class="border border-gray-300 rounded-md px-3 py-2 text-sm w-64"
            >
            <button
                type="submit"
                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-slate-900 text-white hover:bg-slate-800"
            >
                Buscar
            </button>
        </form>

        <a href="{{ route('app.customers.create') }}"
           class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md bg-emerald-600 text-white hover:bg-emerald-500">
            + Nuevo cliente
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Nombre</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">DNI</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Email</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-600">Teléfono</th>
                    <th class="px-4 py-2 text-right font-semibold text-gray-600">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-4 py-2">
                            <a href="{{ route('app.customers.show', $customer) }}" class="text-slate-900 font-medium hover:underline">
                                {{ $customer->name }} {{ $customer->surname }}
                            </a>
                        </td>
                        <td class="px-4 py-2">{{ $customer->dni }}</td>
                        <td class="px-4 py-2">{{ $customer->email }}</td>
                        <td class="px-4 py-2">{{ $customer->phone }}</td>
                        <td class="px-4 py-2 text-right space-x-2">
                            <a href="{{ route('app.customers.edit', $customer) }}"
                               class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md border border-gray-300 hover:bg-gray-100">
                                Editar
                            </a>

                            <form action="{{ route('app.customers.destroy', $customer) }}" method="POST" class="inline-block"
                                  onsubmit="return confirm('¿Eliminar este cliente?');">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md border border-red-200 text-red-700 hover:bg-red-50"
                                >
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                            No hay clientes cargados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $customers->links() }}
        </div>
    </div>
</x-app-layout>
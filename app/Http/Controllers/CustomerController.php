<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;


class CustomerController extends Controller
{
    /**
     * Listado de clientes.
     */
    public function index(Request $request)
    {
        $search = $request->get('q');

        $customers = Customer::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('customers.index', compact('customers', 'search'));
    }

    /**
     * Formulario de creación.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Guarda un nuevo cliente.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'surname' => ['nullable', 'string', 'max:255'],
            'dni'     => ['nullable', 'string', 'max:50'],
            'email'   => ['nullable', 'email', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'city'    => ['nullable', 'string', 'max:255'],
            'notes'   => ['nullable', 'string'],
        ]);

        Customer::create($data);

        return redirect()
            ->route('app.customers.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    /**
     * Muestra detalle de un cliente.
     */
    public function show(Customer $customer)
    {
        $customer->load(['rentalContracts.vehicle', 'documents']);
        return view('customers.show', compact('customer'));
    }

    /**
     * Formulario de edición.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Actualiza un cliente.
     */
    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'surname' => ['nullable', 'string', 'max:255'],
            'dni'     => ['nullable', 'string', 'max:50'],
            'email'   => ['nullable', 'email', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'city'    => ['nullable', 'string', 'max:255'],
            'notes'   => ['nullable', 'string'],
        ]);

        $customer->update($data);

        return redirect()
            ->route('app.customers.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Elimina un cliente.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()
            ->route('app.customers.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}
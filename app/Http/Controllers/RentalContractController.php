<?php

namespace App\Http\Controllers;

use App\Models\RentalContract;
use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class RentalContractController extends Controller
{
    public function index()
    {
        $contracts = RentalContract::with(['customer', 'vehicle'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('rental_contracts.index', compact('contracts'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $vehicles  = Vehicle::orderBy('plate')->get();

        return view('rental_contracts.create', compact('customers', 'vehicles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'     => ['required', 'exists:customers,id'],
            'vehicle_id'      => ['required', 'exists:vehicles,id'],
            'start_date'      => ['required', 'date'],
            'end_date'        => ['required', 'date', 'after_or_equal:start_date'],
            'price_per_day'   => ['required', 'numeric', 'min:0'],
            'included_km'     => ['nullable', 'integer', 'min:0'],
            'max_km'          => ['nullable', 'integer', 'min:0'],
            'status'          => ['required', 'string', 'max:50'],
            'notes'           => ['nullable', 'string'],
        ]);

        // TODO: validar solapamiento de reservas para el vehículo

        RentalContract::create($data);

        return redirect()
            ->route('rental-contracts.index')
            ->with('success', 'Contrato creado correctamente.');
    }

    public function show(RentalContract $rentalContract)
    {
        $rentalContract->load(['customer', 'vehicle', 'invoice', 'documents']);

        return view('rental_contracts.show', compact('rentalContract'));
    }

    public function edit(RentalContract $rentalContract)
    {
        $customers = Customer::orderBy('name')->get();
        $vehicles  = Vehicle::orderBy('plate')->get();

        return view('rental_contracts.edit', compact('rentalContract', 'customers', 'vehicles'));
    }

    public function update(Request $request, RentalContract $rentalContract)
    {
        $data = $request->validate([
            'customer_id'     => ['required', 'exists:customers,id'],
            'vehicle_id'      => ['required', 'exists:vehicles,id'],
            'start_date'      => ['required', 'date'],
            'end_date'        => ['required', 'date', 'after_or_equal:start_date'],
            'price_per_day'   => ['required', 'numeric', 'min:0'],
            'included_km'     => ['nullable', 'integer', 'min:0'],
            'max_km'          => ['nullable', 'integer', 'min:0'],
            'status'          => ['required', 'string', 'max:50'],
            'notes'           => ['nullable', 'string'],
        ]);

        // TODO: validar solapamiento

        $rentalContract->update($data);

        return redirect()
            ->route('rental-contracts.index')
            ->with('success', 'Contrato actualizado correctamente.');
    }

    public function destroy(RentalContract $rentalContract)
    {
        $rentalContract->delete();

        return redirect()
            ->route('rental-contracts.index')
            ->with('success', 'Contrato eliminado correctamente.');
    }
}

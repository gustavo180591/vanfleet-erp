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

        return view('rental-contracts.index', compact('contracts'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $vehicles = Vehicle::orderBy('plate')->get();

        return view('rental-contracts.create', compact('customers', 'vehicles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'rental_type' => 'required|in:daily,weekly,monthly',
            'monthly_rate' => 'required|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'terms' => 'nullable|string',
        ]);

        $contract = RentalContract::create($validated);

        return redirect()
            ->route('app.rental-contracts.show', $contract)
            ->with('success', 'Contrato creado exitosamente');
    }

    public function show(RentalContract $contract)
    {
        $contract->load(['customer', 'vehicle', 'payments']);
        return view('rental-contracts.show', compact('contract'));
    }

    public function edit(RentalContract $contract)
    {
        $customers = Customer::orderBy('name')->get();
        $vehicles = Vehicle::orderBy('plate')->get();

        return view('rental-contracts.edit', compact('contract', 'customers', 'vehicles'));
    }

    public function update(Request $request, RentalContract $contract)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'rental_type' => 'required|in:daily,weekly,monthly',
            'monthly_rate' => 'required|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,completed,cancelled',
            'terms' => 'nullable|string',
        ]);

        $contract->update($validated);

        return redirect()
            ->route('app.rental-contracts.show', $contract)
            ->with('success', 'Contrato actualizado exitosamente');
    }

    public function destroy(RentalContract $contract)
    {
        $contract->delete();

        return redirect()
            ->route('app.rental-contracts.index')
            ->with('success', 'Contrato eliminado exitosamente');
    }
}

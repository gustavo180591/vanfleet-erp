<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::orderBy('plate')->paginate(15);

        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'plate'                => ['required', 'string', 'max:50', 'unique:vehicles,plate'],
            'brand'                => ['nullable', 'string', 'max:255'],
            'model'                => ['required', 'string', 'max:255'],
            'purchase_date'        => ['nullable', 'date'],
            'current_km'           => ['nullable', 'integer', 'min:0'],
            'status'               => ['required', 'string', 'max:50'],
            'daily_rate'           => ['required', 'numeric', 'min:0'],
            'km_included_per_day'  => ['nullable', 'integer', 'min:0'],
            'extra_km_price'       => ['nullable', 'numeric', 'min:0'],
            'notes'                => ['nullable', 'string'],
        ]);

        Vehicle::create($data);

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Vehículo creado correctamente.');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load(['rentalContracts', 'maintenances']);

        return view('vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'plate'                => ['required', 'string', 'max:50', 'unique:vehicles,plate,' . $vehicle->id],
            'brand'                => ['nullable', 'string', 'max:255'],
            'model'                => ['required', 'string', 'max:255'],
            'purchase_date'        => ['nullable', 'date'],
            'current_km'           => ['nullable', 'integer', 'min:0'],
            'status'               => ['required', 'string', 'max:50'],
            'daily_rate'           => ['required', 'numeric', 'min:0'],
            'km_included_per_day'  => ['nullable', 'integer', 'min:0'],
            'extra_km_price'       => ['nullable', 'numeric', 'min:0'],
            'notes'                => ['nullable', 'string'],
        ]);

        $vehicle->update($data);

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Vehículo actualizado correctamente.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()
            ->route('vehicles.index')
            ->with('success', 'Vehículo eliminado correctamente.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    /**
     * Listado de mantenimientos.
     */
    public function index(Request $request)
    {
        $vehicleId = $request->get('vehicle_id');
        $type = $request->get('type');

        $maintenances = Maintenance::with('vehicle')
            ->when($vehicleId, fn ($q) => $q->where('vehicle_id', $vehicleId))
            ->when($type, fn ($q) => $q->where('type', $type))
            ->orderByDesc('scheduled_date')
            ->paginate(15)
            ->withQueryString();

        $vehicles = Vehicle::orderBy('plate')->get();

        return view('maintenances.index', compact('maintenances', 'vehicles', 'vehicleId', 'type'));
    }

    /**
     * Formulario de creación.
     */
    public function create(Request $request)
    {
        $vehicles = Vehicle::orderBy('plate')->get();
        $vehicleId = $request->get('vehicle_id');

        return view('maintenances.create', compact('vehicles', 'vehicleId'));
    }

    /**
     * Guarda un mantenimiento.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicle_id'     => ['required', 'exists:vehicles,id'],
            'type'           => ['required', 'string', 'max:50'],
            'scheduled_date' => ['nullable', 'date'],
            'done_date'      => ['nullable', 'date'],
            'cost'           => ['nullable', 'numeric', 'min:0'],
            'notes'          => ['nullable', 'string'],
        ]);

        Maintenance::create($data);

        return redirect()
            ->route('maintenances.index')
            ->with('success', 'Mantenimiento creado correctamente.');
    }

    /**
     * Muestra detalle de un mantenimiento.
     */
    public function show(Maintenance $maintenance)
    {
        $maintenance->load('vehicle');

        return view('maintenances.show', compact('maintenance'));
    }

    /**
     * Formulario de edición.
     */
    public function edit(Maintenance $maintenance)
    {
        $vehicles = Vehicle::orderBy('plate')->get();

        return view('maintenances.edit', compact('maintenance', 'vehicles'));
    }

    /**
     * Actualiza un mantenimiento.
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        $data = $request->validate([
            'vehicle_id'     => ['required', 'exists:vehicles,id'],
            'type'           => ['required', 'string', 'max:50'],
            'scheduled_date' => ['nullable', 'date'],
            'done_date'      => ['nullable', 'date'],
            'cost'           => ['nullable', 'numeric', 'min:0'],
            'notes'          => ['nullable', 'string'],
        ]);

        $maintenance->update($data);

        return redirect()
            ->route('maintenances.show', $maintenance)
            ->with('success', 'Mantenimiento actualizado correctamente.');
    }

    /**
     * Elimina un mantenimiento.
     */
    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();

        return redirect()
            ->route('maintenances.index')
            ->with('success', 'Mantenimiento eliminado correctamente.');
    }
}

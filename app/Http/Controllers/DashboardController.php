<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\RentalContract;
use App\Models\Maintenance;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get vehicle statistics
        $totalVehicles = Vehicle::count();
        $availableVehicles = Vehicle::where('status', 'available')->count();
        $inMaintenance = Vehicle::where('status', 'maintenance')->count();
        $unavailableVehicles = Vehicle::where('status', 'rented')->count();

        // Get active rentals count
        $activeRentals = 0; // Default value
        try {
            if (Schema::hasTable('rental_contracts') && 
                Schema::hasColumn('rental_contracts', 'end_date') && 
                Schema::hasColumn('rental_contracts', 'start_date')) {
                $activeRentals = RentalContract::where('end_date', '>=', now())
                    ->where('start_date', '<=', now())
                    ->count();
            }
        } catch (\Exception $e) {
            // Log error but don't break the dashboard
            \Log::error('Error getting active rentals count: ' . $e->getMessage());
        }

        // Get pending maintenance count
        $pendingMaintenance = 0; // Default value
        try {
            if (Schema::hasTable('maintenances') && 
                Schema::hasColumn('maintenances', 'status')) {
                $pendingMaintenance = Maintenance::where('status', 'scheduled')
                    ->orWhere('status', 'in_progress')
                    ->count();
            }
        } catch (\Exception $e) {
            // Log error but don't break the dashboard
            \Log::error('Error getting pending maintenance count: ' . $e->getMessage());
        }

        // Calculate monthly revenue
        $monthlyRevenue = 0; // Default value
        try {
            if (Schema::hasTable('invoices') && 
                Schema::hasColumn('invoices', 'created_at') && 
                Schema::hasColumn('invoices', 'total_amount')) {
                $monthlyRevenue = Invoice::whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->sum('total_amount');
            }
        } catch (\Exception $e) {
            // Log error but don't break the dashboard
            \Log::error('Error calculating monthly revenue: ' . $e->getMessage());
        }

        // Generate recent activities (you can customize this based on your needs)
        $recentActivities = $this->getRecentActivities();

        return view('dashboard', [
            'totalVehicles' => $totalVehicles,
            'availableVehicles' => $availableVehicles,
            'inMaintenance' => $inMaintenance,
            'unavailableVehicles' => $unavailableVehicles,
            'activeRentals' => $activeRentals,
            'pendingMaintenance' => $pendingMaintenance,
            'monthlyRevenue' => $monthlyRevenue,
            'recentActivities' => $recentActivities,
        ]);
    }

    /**
     * Get recent activities for the dashboard.
     *
     * @return array
     */
    protected function getRecentActivities()
    {
        $activities = [];
        $now = now();

        // Get recent rentals
        $recentRentals = RentalContract::with('vehicle', 'customer')
            ->latest()
            ->take(3)
            ->get();

        foreach ($recentRentals as $rental) {
            $activities[] = [
                'icon' => 'fa-file-contract',
                'title' => 'Nuevo contrato de alquiler',
                'description' => "Contrato #{$rental->id} para {$rental->customer->name} - {$rental->vehicle->make} {$rental->vehicle->model}",
                'time' => $rental->created_at->diffForHumans(),
            ];
        }

        // Get recent maintenance
        $recentMaintenance = Maintenance::with('vehicle')
            ->latest()
            ->take(3 - count($activities))
            ->get();

        foreach ($recentMaintenance as $maintenance) {
            $activities[] = [
                'icon' => 'fa-tools',
                'title' => 'Mantenimiento programado',
                'description' => "{$maintenance->type} para {$maintenance->vehicle->make} {$maintenance->vehicle->model}",
                'time' => $maintenance->created_at->diffForHumans(),
            ];
        }

        // If we still don't have enough activities, add some default ones
        if (count($activities) < 3) {
            $activities[] = [
                'icon' => 'fa-info-circle',
                'title' => 'Bienvenido al sistema',
                'description' => 'Su panel de control está listo para usar.',
                'time' => 'Hace unos momentos',
            ];
        }

        return $activities;
    }
}

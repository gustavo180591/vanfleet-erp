<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\RentalContract;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    /**
     * Listado de facturas.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');

        $invoices = Invoice::with(['rentalContract.customer', 'rentalContract.vehicle'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderByDesc('issue_date')
            ->paginate(15)
            ->withQueryString();

        return view('invoices.index', compact('invoices', 'status'));
    }

    /**
     * Formulario para crear una factura (normalmente desde un contrato).
     */
    public function create(Request $request)
    {
        $contractId = $request->get('rental_contract_id');
        $rentalContracts = RentalContract::with(['customer', 'vehicle'])
            ->orderByDesc('created_at')
            ->get();

        return view('invoices.create', compact('rentalContracts', 'contractId'));
    }

    /**
     * Guarda una nueva factura.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'rental_contract_id' => ['required', 'exists:rental_contracts,id'],
            'issue_date'         => ['required', 'date'],
            'due_date'           => ['nullable', 'date'],
            'amount_subtotal'    => ['required', 'numeric', 'min:0'],
            'amount_tax'         => ['required', 'numeric', 'min:0'],
            'amount_total'       => ['required', 'numeric', 'min:0'],
            'status'             => ['required', 'string', 'max:50'],
        ]);

        // Generación simple de número de factura (después podés hacer algo más serio)
        $data['invoice_number'] = $this->generateInvoiceNumber();
        $data['verifactu_status'] = 'pending';

        Invoice::create($data);

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Factura creada correctamente.');
    }

    /**
     * Muestra detalle de una factura.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['rentalContract.customer', 'rentalContract.vehicle']);

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Formulario de edición.
     */
    public function edit(Invoice $invoice)
    {
        $invoice->load('rentalContract');

        return view('invoices.edit', compact('invoice'));
    }

    /**
     * Actualiza una factura.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'issue_date'      => ['required', 'date'],
            'due_date'        => ['nullable', 'date'],
            'amount_subtotal' => ['required', 'numeric', 'min:0'],
            'amount_tax'      => ['required', 'numeric', 'min:0'],
            'amount_total'    => ['required', 'numeric', 'min:0'],
            'status'          => ['required', 'string', 'max:50'],
            'verifactu_status'=> ['nullable', 'string', 'max:50'],
        ]);

        $invoice->update($data);

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Factura actualizada correctamente.');
    }

    /**
     * Elimina una factura.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Factura eliminada correctamente.');
    }

    /**
     * Genera un número de factura básico.
     * Ej: INV-2025-000001
     */
    protected function generateInvoiceNumber(): string
    {
        $year = now()->year;

        $last = Invoice::whereYear('issue_date', $year)
            ->orderByDesc('id')
            ->first();

        $nextNumber = $last ? ((int) Str::afterLast($last->invoice_number, '-') + 1) : 1;

        return sprintf('INV-%s-%06d', $year, $nextNumber);
    }
}

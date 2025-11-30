<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\RentalContract;
use App\Models\Customer;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Mostrar listado de facturas.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        
        $invoices = Invoice::with(['customer', 'rentalContract'])
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

        $customers = Customer::orderBy('name')->get();

        return view('invoices.create', compact('rentalContracts', 'customers', 'contractId'));
    }

    /**
     * Almacenar una nueva factura.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rental_contract_id' => 'required|exists:rental_contracts,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Calcular totales
        $subtotal = collect($validated['items'])->sum(function ($item) {
            return $item['quantity'] * $item['unit_price'];
        });

        $tax = $subtotal * 0.21; // Asumiendo 21% de IVA
        $total = $subtotal + $tax;

        // Crear factura
        $invoice = new Invoice();
        $invoice->rental_contract_id = $validated['rental_contract_id'];
        $invoice->invoice_number = 'INV-' . strtoupper(uniqid());
        $invoice->issue_date = $validated['issue_date'];
        $invoice->due_date = $validated['due_date'];
        $invoice->status = $validated['status'];
        $invoice->subtotal = $subtotal;
        $invoice->tax = $tax;
        $invoice->total = $total;
        $invoice->notes = $validated['notes'] ?? null;
        $invoice->save();

        // Guardar items
        foreach ($validated['items'] as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()
            ->route('app.invoices.show', $invoice)
            ->with('success', 'Factura creada exitosamente');
    }

    /**
     * Mostrar una factura específica.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'rentalContract', 'items']);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Mostrar formulario de edición de factura.
     */
    public function edit(Invoice $invoice)
    {
        $rentalContracts = RentalContract::with(['customer', 'vehicle'])
            ->orderByDesc('created_at')
            ->get();

        $customers = Customer::orderBy('name')->get();
        $invoice->load('items');

        return view('invoices.edit', compact('invoice', 'rentalContracts', 'customers'));
    }

    /**
     * Actualizar una factura existente.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'rental_contract_id' => 'required|exists:rental_contracts,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Calcular totales
        $subtotal = collect($validated['items'])->sum(function ($item) {
            return $item['quantity'] * $item['unit_price'];
        });

        $tax = $subtotal * 0.21; // Asumiendo 21% de IVA
        $total = $subtotal + $tax;

        // Actualizar factura
        $invoice->rental_contract_id = $validated['rental_contract_id'];
        $invoice->issue_date = $validated['issue_date'];
        $invoice->due_date = $validated['due_date'];
        $invoice->status = $validated['status'];
        $invoice->subtotal = $subtotal;
        $invoice->tax = $tax;
        $invoice->total = $total;
        $invoice->notes = $validated['notes'] ?? null;
        $invoice->save();

        // Eliminar items existentes
        $invoice->items()->delete();

        // Guardar nuevos items
        foreach ($validated['items'] as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return redirect()
            ->route('app.invoices.show', $invoice)
            ->with('success', 'Factura actualizada exitosamente');
    }

    /**
     * Eliminar una factura.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->items()->delete();
        $invoice->delete();

        return redirect()
            ->route('app.invoices.index')
            ->with('success', 'Factura eliminada exitosamente');
    }

    /**
     * Descargar factura en PDF.
     */
    public function download(Invoice $invoice)
    {
        $invoice->load(['customer', 'rentalContract', 'items']);
        $pdf = \PDF::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download("factura-{$invoice->invoice_number}.pdf");
    }

    /**
     * Enviar factura por correo electrónico.
     */
    public function sendEmail(Request $request, Invoice $invoice)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $invoice->load(['customer', 'rentalContract', 'items']);

        \Mail::to($request->email)->send(new \App\Mail\InvoiceSent($invoice));

        return back()->with('success', 'Factura enviada por correo electrónico');
    }
}

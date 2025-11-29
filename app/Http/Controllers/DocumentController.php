<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Customer;
use App\Models\RentalContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Listado de documentos.
     */
    public function index(Request $request)
    {
        $customerId = $request->get('customer_id');
        $contractId = $request->get('rental_contract_id');
        $type       = $request->get('type');

        $documents = Document::with(['customer', 'rentalContract'])
            ->when($customerId, fn ($q) => $q->where('customer_id', $customerId))
            ->when($contractId, fn ($q) => $q->where('rental_contract_id', $contractId))
            ->when($type, fn ($q) => $q->where('type', $type))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $customers = Customer::orderBy('name')->get();
        $contracts = RentalContract::orderByDesc('created_at')->get();

        return view('documents.index', compact('documents', 'customers', 'contracts', 'customerId', 'contractId', 'type'));
    }

    /**
     * Formulario de subida de documento genérico.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $contracts = RentalContract::orderByDesc('created_at')->get();

        return view('documents.create', compact('customers', 'contracts'));
    }

    /**
     * Guarda un documento subido.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'        => ['nullable', 'exists:customers,id'],
            'rental_contract_id' => ['nullable', 'exists:rental_contracts,id'],
            'type'               => ['required', 'string', 'max:50'],
            'file'               => ['required', 'file', 'max:10240'], // 10MB
        ]);

        $file = $request->file('file');

        $path = $file->store('documents');

        Document::create([
            'customer_id'        => $data['customer_id'] ?? null,
            'rental_contract_id' => $data['rental_contract_id'] ?? null,
            'type'               => $data['type'],
            'file_path'          => $path,
            'original_name'      => $file->getClientOriginalName(),
            'mime_type'          => $file->getClientMimeType(),
        ]);

        return redirect()
            ->route('documents.index')
            ->with('success', 'Documento subido correctamente.');
    }

    /**
     * Descarga / muestra un documento.
     */
    public function show(Document $document)
    {
        if (! Storage::exists($document->file_path)) {
            abort(404);
        }

        // Podés cambiar a ->download(...) si preferís forzar descarga
        return Storage::download($document->file_path, $document->original_name);
    }

    /**
     * Formulario de edición (solo metadatos, no archivo).
     */
    public function edit(Document $document)
    {
        $customers = Customer::orderBy('name')->get();
        $contracts = RentalContract::orderByDesc('created_at')->get();

        return view('documents.edit', compact('document', 'customers', 'contracts'));
    }

    /**
     * Actualiza metadatos del documento.
     */
    public function update(Request $request, Document $document)
    {
        $data = $request->validate([
            'customer_id'        => ['nullable', 'exists:customers,id'],
            'rental_contract_id' => ['nullable', 'exists:rental_contracts,id'],
            'type'               => ['required', 'string', 'max:50'],
        ]);

        $document->update($data);

        return redirect()
            ->route('documents.index')
            ->with('success', 'Documento actualizado correctamente.');
    }

    /**
     * Elimina un documento (y su archivo).
     */
    public function destroy(Document $document)
    {
        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }

        $document->delete();

        return redirect()
            ->route('documents.index')
            ->with('success', 'Documento eliminado correctamente.');
    }
}

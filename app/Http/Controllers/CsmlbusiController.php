<?php

namespace App\Http\Controllers;

use App\Models\Csmlbusi;
use App\Models\DocumentUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CsmlbusiController extends Controller
{
    /**
     * Display a list of Csmlbusi records with optional search functionality.
     */
    public function index(Request $request)
    {
        $query = Csmlbusi::with('documents');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('client_name', 'like', "%$search%")
                    ->orWhere('business_type', 'like', "%$search%")
                    ->orWhere('year', 'like', "%$search%")
                    ->orWhere('expiry_date', 'like', "%$search%")
                    ->orWhere('bid_status', 'like', "%$search%");
            });
        }

        $documents = $query->paginate(10);

        if ($request->ajax()) {
            return response()->json($documents);
        }

        return view('admin.csmlbusi.index', compact('documents'));
    }

    /**
     * Show the form for creating a new Csmlbusi record.
     */
    public function create()
    {
        return view('admin.csmlbusi.create');
    }

    /**
     * Store a newly created Csmlbusi record along with any uploaded documents.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'business_type' => 'required|string|in:tender,quotation,prequalification',
            'year' => 'required|integer',
            'amount' => 'nullable|string',
            'expiry_date' => 'required|date',
            'bid_status' => 'required|in:Progress,won,lost',
            'files.*' => 'nullable|file|mimes:pdf,docx,xlsx',
        ]);

        try {
            // Create the Csmlbusi record
            $csmlbusi = Csmlbusi::create($request->only([
                'client_name', 'business_type', 'year', 'amount','expiry_date', 'bid_status'
            ]));

            // Handle file uploads
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $filePath = $file->store('public/csmlbusi_files');
                    $documentName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $documentType = $file->getClientOriginalExtension();

                    // Create the associated document record
                    $csmlbusi->documents()->create([
                        'file_path' => $filePath,
                        'document_title' => $documentName,
                        'document_type' => $documentType,
                    ]);
                }
            }

            return redirect()->route('admin.csmlbusi.create')
                ->with('success', 'Business document created successfully.');

        } catch (\Exception $e) {
            return redirect()->route('admin.csmlbusi.create')
                ->with('error', 'Failed to create the business document. Please try again.');
        }
    }

    /**
     * Show the form for editing a specific Csmlbusi record.
     */
    public function edit($id)
    {
        $csmlbusi = Csmlbusi::with('documents')->findOrFail($id);
        return view('admin.csmlbusi.edit', compact('csmlbusi'));
    }

    /**
     * Update a specific Csmlbusi record along with any uploaded documents.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'business_type' => 'required|string|in:tender,quotation,prequalification',
            'year' => 'required|integer',
            'amount' => 'nullable|numeric', // Ensure amount is numeric
            'expiry_date' => 'required|date',
            'bid_status' => 'required|in:Progress,won,lost',
            'files.*' => 'nullable|file|mimes:pdf,docx,xlsx',
        ]);

        $csmlbusi = Csmlbusi::findOrFail($id);

        try {
            // Update the Csmlbusi record
            $csmlbusi->update($request->only([
                'client_name', 'business_type', 'year', 'amount', 'expiry_date', 'bid_status'
            ]));

            // Handle new file uploads
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $filePath = $file->store('public/csmlbusi_files');
                    $documentName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $documentType = $file->getClientOriginalExtension();

                    // Create the associated document record
                    $csmlbusi->documents()->create([
                        'file_path' => $filePath,
                        'document_title' => $documentName,
                        'document_type' => $documentType,
                    ]);
                }
            }

            return redirect()->route('admin.csmlbusi.index')
                ->with('success', 'Business document updated successfully.');

        } catch (\Exception $e) {
            return redirect()->route('admin.csmlbusi.edit', $id)
                ->with('error', 'Failed to update the business document. Please try again.');
        }
    }

    /**
     * Delete a specific Csmlbusi record and its associated documents.
     */
    public function destroy($id)
    {
        $csmlbusi = Csmlbusi::findOrFail($id);

        try {
            // Delete associated documents
            foreach ($csmlbusi->documents as $document) {
                Storage::delete($document->file_path);
                $document->delete();
            }

            // Delete the Csmlbusi record
            $csmlbusi->delete();

            return response()->json(['success' => 'Business and associated files deleted successfully!']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete the business. Please try again.'], 500);
        }
    }

    /**
     * Delete a specific document associated with a Csmlbusi record.
     */
    public function deleteDocument($id)
    {
        $document = DocumentUpload::findOrFail($id);

        try {
            Storage::delete($document->file_path);
            $document->delete();

            return redirect()->route('admin.csmlbusi.edit', $document->csmlbusi_id)
                ->with('success', 'Document deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->route('admin.csmlbusi.edit', $document->csmlbusi_id)
                ->with('error', 'Failed to delete the document. Please try again.');
        }
    }
}

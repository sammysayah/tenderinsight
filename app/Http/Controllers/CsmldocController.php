<?php 
namespace App\Http\Controllers;


use App\Models\Csmldoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CsmldocController extends Controller
{
    public function index()
    {
        $documents = Csmldoc::paginate(10); // Paginate documents
        return view('admin.csmldoc.index', compact('documents'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        // Perform the search query on the documents table
        $documents = Csmldoc::where('document_name', 'like', '%' . $query . '%')
            ->orWhere('document_type', 'like', '%' . $query . '%')
            ->orWhere('year', 'like', '%' . $query . '%')
            ->orWhere('expiry_date', 'like', '%' . $query . '%')
            ->get();

        // Return documents as JSON
        return response()->json($documents);
    }

    public function create()
    {
        return view('admin.csmldoc.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_name' => 'required|string|max:255',
            'document_type' => 'required|string',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'expiry_date' => 'required|date|after_or_equal:today',
          'file' => 'required|file|mimes:pdf,doc,docx|max:10485760',
        ]);

        // Check for duplicate entries for the same type and year
        $existing = Csmldoc::where('document_type', $request->document_type)
            ->where('year', $request->year)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withErrors(['file' => 'A document of type "' . $request->document_type . '" for the year ' . $request->year . ' already exists.'])
                ->withInput();
        }

        // Save the file to storage
        $filePath = $request->file('file')->store('documents', 'public');
        $documentTitle = $request->file('file')->getClientOriginalName();

        // Save the document record
        Csmldoc::create([
            'document_name' => $request->document_name,
            'document_type' => $request->document_type,
            'year' => $request->year,
            'expiry_date' => $request->expiry_date,
            'document_title' => $documentTitle, // Save the file name
            'file_path' => $filePath,
        ]);

        return redirect()->route('admin.csmldoc.create')->with('success', 'Document uploaded successfully!');
    }

    public function edit($id)
    {
        $document = Csmldoc::findOrFail($id);
        return view('admin.csmldoc.edit', compact('document'));
    }

    public function update(Request $request, $id)
    {
        $document = Csmldoc::findOrFail($id);

        $request->validate([
            'document_name' => 'required|string|max:255',
            'document_type' => 'required|string',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'expiry_date' => 'required|date|after_or_equal:today',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10485760',
        ]);

        // Check for duplicate entries for the same type and year (ignore the current record)
        $existing = Csmldoc::where('document_type', $request->document_type)
            ->where('year', $request->year)
            ->where('id', '!=', $id)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->withErrors(['file' => 'A document of type "' . $request->document_type . '" for the year ' . $request->year . ' already exists.'])
                ->withInput();
        }

        // Update file if provided
        if ($request->hasFile('file')) {
            // Delete old file
            if ($document->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }

            // Save new file
            $filePath = $request->file('file')->store('documents', 'public');
            $documentTitle = $request->file('file')->getClientOriginalName(); // Extract new file name

            $document->update([
                'file_path' => $filePath,
                'document_title' => $documentTitle, // Update document title with new file name
            ]);
        }

        // Update other fields
        $document->update([
            'document_name' => $request->document_name,
            'document_type' => $request->document_type,
            'year' => $request->year,
            'expiry_date' => $request->expiry_date,
        ]);

        return redirect()->route('admin.csmldoc.edit', $id)->with('success', 'Document updated successfully!');
    }

    public function destroy($id)
    {
        $document = Csmldoc::findOrFail($id);

        // Delete the document file if it exists
        if (file_exists(public_path('storage/' . $document->file_path))) {
            unlink(public_path('storage/' . $document->file_path));
        }

        // Delete the document record
        $document->delete();

        return response()->json(['success' => 'Document deleted successfully.']);
    }

    public function download($id)
    {
        // Retrieve the document by ID
        $document = Csmldoc::findOrFail($id);
    
        // Ensure document_title exists (original file name)
        if (!$document->document_title) {
            return response()->json(['error' => 'Document title not found.'], 404);
        }
    
        // Construct the full file path (assuming files are stored in 'public/documents')
        $filePath = storage_path('app/public/' . $document->file_path);
    
        // Check if the file exists
        if (file_exists($filePath)) {
            // Return the file as a download with the original document title as the filename
            return response()->download($filePath, $document->document_title);
        }
    
        // Return an error response if the file doesn't exist
        return response()->json(['error' => 'File not found.'], 404);
    }
    
}

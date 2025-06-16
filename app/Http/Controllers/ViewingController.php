<?php

namespace App\Http\Controllers;

use App\Models\Csmldoc;
use App\Models\Csmlbusi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Facades\Auth;

class ViewingController extends Controller
{
    /**
     * View CSML Documents (For Admin and User)
     */
    public function viewCsmldocs(Request $request)
    {
        $user = Auth::user();
        
        if ($user->usertype === 'admin') {
            $documents = Csmldoc::paginate(10); // Admin can view all CSML docs
            return view('admin.csmldocs', compact('documents'));
        } else {
            $documents = Csmldoc::paginate(10); // Users can view their own documents
            return view('user.csmldocs', compact('documents'));
        }
    }

    /**
     * Search CSML Documents (For Admin and User)
     */
    public function searchCsmldocs(Request $request)
    {
        $query = $request->input('query');
        $user = Auth::user();
        
        if ($user->usertype === 'admin') {
            $documents = Csmldoc::where('document_name', 'LIKE', "%{$query}%")
                ->orWhere('document_type', 'LIKE', "%{$query}%")
                ->orWhere('year', 'LIKE', "%{$query}%")
                ->get();
        } else {
            $documents = Csmldoc::where('user_id', $user->id)
                ->where(function ($query) use ($request) {
                    $query->where('document_name', 'LIKE', "%{$request->input('query')}%")
                        ->orWhere('document_type', 'LIKE', "%{$request->input('query')}%")
                        ->orWhere('year', 'LIKE', "%{$request->input('query')}%");
                })
                ->get();
        }

        return response()->json($documents);
    }

    /**
     * View CSML Businesses (For Admin and User)
     */
    public function viewCsmlbusis(Request $request)
    {
        $user = Auth::user();

        if ($user->usertype === 'admin') {
            $businesses = Csmlbusi::paginate(10); // Admin can view all CSML Businesses
            return view('admin.csmlbus', compact('businesses'));
        } else {
            $businesses = Csmlbusi::paginate(10); 
            return view('user.csmlbus', compact('businesses'));
        }
    }

    /**
     * Download Single CSML Document (For Admin and User)
     */
    public function download($id)
    {
        $document = Csmldoc::findOrFail($id);
        $user = Auth::user();
    
        \Log::info("Download request by user: {$user->id} (UserType: {$user->usertype}) for document ID: {$document->id}");
        
        // If the user is an admin or the document belongs to the current user
        if ($user->usertype === 'admin' || $document->user_id === $user->id) {
            if (!$document->document_title) {
                \Log::error("Document title is missing for document ID: {$document->id}");
                return response()->json(['error' => 'Document title not found.'], 404);
            }
    
            $filePath = storage_path('app/public/' . $document->file_path);
    
            \Log::info("File path for document: {$filePath}");
    
            if (file_exists($filePath)) {
                \Log::info("File found. Preparing to download: {$filePath}");
                return response()->download($filePath, $document->document_title);
            }
    
            \Log::error("File not found for document ID: {$document->id} at path: {$filePath}");
            return response()->json(['error' => 'File not found.'], 404);
        }
    
        \Log::error("Unauthorized access attempt for document ID: {$document->id} by user ID: {$user->id}");
        return response()->json(['error' => 'Unauthorized access to this document.'], 403);
    }

    /**
     * View Businesses and Their Documents (For Admin)
     */
    public function index()
    {
        $businesses = Csmlbusi::with('documents')->paginate(10);
        return view('admin.csmlbus', compact('businesses'));
    }

    /**
     * Download Documents for a Business (For Admin and User)
     */
    public function downloadDocuments($businessId)
{
    $business = Csmlbusi::findOrFail($businessId);
    $user = Auth::user();

    // Allow only admins and users to download
    if (!in_array($user->usertype, ['admin', 'user'])) {
        \Log::warning("Unauthorized access attempt by user ID: {$user->id}");
        return back()->with('error', 'Unauthorized access to these documents.');
    }

    $documents = $business->documents;

    if ($documents->isEmpty()) {
        \Log::info("No documents found for Business ID: {$business->id}");
        return back()->with('error', 'No documents available for download.');
    }

    // Set client name for ZIP file
    $clientName = $business->client_name ?: 'business';
    $zipFileName = "{$clientName}_documents.zip";

    \Log::info("Creating ZIP file: {$zipFileName} for Business ID: {$business->id} by User ID: {$user->id}");

    // Create ZIP file
    $zip = new ZipArchive();
    $zipPath = storage_path("app/public/{$zipFileName}");

    if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
        foreach ($documents as $document) {
            $filePath = storage_path("app/{$document->file_path}");

            if (file_exists($filePath)) {
                $zip->addFile($filePath, basename($filePath));
            } else {
                \Log::warning("File not found: {$filePath} for Document ID: {$document->id}");
            }
        }
        $zip->close();
    } else {
        \Log::error("Failed to create ZIP file: {$zipFileName}");
        return back()->with('error', 'Unable to create ZIP file.');
    }

    // Return ZIP file for download
    return response()->download($zipPath, $zipFileName, [
        'Content-Type' => 'application/zip'
    ])->deleteFileAfterSend(true);
}

}

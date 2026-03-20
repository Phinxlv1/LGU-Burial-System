<?php

namespace App\Http\Controllers;

use App\Models\BurialPermit;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * POST /permits/{permit}/documents
     * Upload and attach a document to a permit.
     */
    public function upload(Request $request, BurialPermit $permit)
    {
        $request->validate([
            'document' => [
                'required',
                'file',
                'max:10240', // 10MB
                'mimes:pdf,doc,docx,jpg,jpeg,png,gif',
            ],
        ]);

        $file     = $request->file('document');
        $origName = $file->getClientOriginalName();
        $ext      = $file->getClientOriginalExtension();
        $mime     = $file->getMimeType();

        // Store under permits/{permit_id}/ folder, private disk
        $path = $file->storeAs(
            'permits/' . $permit->id . '/documents',
            $origName,
            'local'
        );

        Document::create([
            'permit_id'   => $permit->id,
            'file_name'   => $origName,
            'file_path'   => $path,
            'file_type'   => $ext,
            'mime_type'   => $mime,
            'uploaded_by' => Auth::id(),
        ]);

        return redirect()->route('permits.show', $permit)
            ->with('success', '"' . $origName . '" attached successfully.');
    }

    /**
     * GET /documents/{document}/download
     * Stream a document for download.
     */
    public function download(Document $document)
    {
        $path = storage_path('app/private/' . $document->file_path);

        abort_unless(file_exists($path), 404, 'File not found.');

        return response()->download($path, $document->file_name, [
            'Content-Type' => $document->mime_type ?? 'application/octet-stream',
        ]);
    }

    /**
     * DELETE /documents/{document}
     * Delete a document and its stored file.
     */
    public function destroy(Document $document)
    {
        $permit = $document->permit;

        // Delete file from storage
        $path = 'private/' . $document->file_path;
        if (Storage::disk('local')->exists($document->file_path)) {
            Storage::disk('local')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('permits.show', $permit)
            ->with('success', 'Document removed successfully.');
    }
}
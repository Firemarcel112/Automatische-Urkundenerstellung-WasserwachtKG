<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $documents = Document::latest()
            ->paginate(25);

        return view('documents.index', [
            'documents' => $documents
        ]);
    }

    public function show(Request $request, Document $document, string $hash)
    {
        if ($document->hash == $hash) {
            return response()->file($document->file_path, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "inline; filename=\"{$document->file_name}\"",
            ]);
        }

        return redirect()->route('documents.index')->with('error', 'Die Datei konnte nicht gefunden werden');
    }

    public function download(Request $request, Document $document, string $hash)
    {
        if ($document->hash == $hash) {
            return response()->download($document->file_path, $document->file_name);
        }
        return redirect()->route('documents.index')->with('error', 'Die Datei konnte nicht gefunden werden');
    }

    public function destroy(Request $request, Document $document)
    {
        $document->delete();

        return redirect()->back()->with('success', 'Die Urkunde wurde erfolgreich gelöscht');
    }
}

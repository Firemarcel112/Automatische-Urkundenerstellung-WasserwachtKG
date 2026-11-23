<?php

namespace App\Http\Controllers;

use \ZipArchive;
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
            $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
            return response()->download($document->file_path, $document->file_name . '.' . $extension);
        }
        return redirect()->route('documents.index')->with('error', 'Die Datei konnte nicht gefunden werden');
    }

    public function bulkDownload(Request $request)
    {
        $document_ids = $request->array('document_ids');

        if (empty($document_ids)) {
            return redirect()->back()->with('error', 'Keine Urkunden ausgewählt');
        }

        $documents = Document::whereIn('document_id', $document_ids)->get();

        if ($documents->isEmpty()) {
            return redirect()->back()->with('error', 'Keine gültigen Urkunden gefunden');
        }

        $zip_file_name = 'urkunden_' . now()->format('Ymd_His') . '.zip';
        $zip_file_path = storage_path('app/' . $zip_file_name);

        $zip = new ZipArchive();
        if ($zip->open($zip_file_path, ZipArchive::CREATE) === true) {
            foreach ($documents as $document) {
                $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                $zip->addFile($document->file_path, $document->file_name . '.' . $extension);
            }
            $zip->close();
        } else {
            return redirect()->back()->with('error', 'Fehler beim Erstellen der Zip-Datei');
        }

        return response()->download($zip_file_path)->deleteFileAfterSend(true);
    }

    public function destroy(Request $request, Document $document)
    {
        $document->delete();

        return redirect()->back()->with('success', 'Die Urkunde wurde erfolgreich gelöscht');
    }
}

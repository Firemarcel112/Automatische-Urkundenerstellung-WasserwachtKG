<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Services\ExportService;
use Illuminate\Http\Request;


class CertificateSettingsController
{
    public function show(Certificate $certificate)
    {
        $field_types = [
            'Text' => 'Einfaches Textfeld',
            'Button' => 'Button oder Checkbox entweder Angekreuzt oder nicht angekreuzt',
        ];

        return view('certificates.settings.show', [
            'certificate' => $certificate,
            'field_types' => $field_types,
        ]);
    }

    public function showDocument(Certificate $certificate)
    {
        if (!file_exists($certificate->file_path)) {
            return redirect()->back()->with('error', 'Die Urkundenvorlage existiert nicht mehr.');
        }

        $filename = $certificate->name . '.pdf';

        return response()->file($certificate->file_path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"{$filename}\"",
        ]);
    }

    public function update(Request $request, Certificate $certificate)
    {
        $normalize_fields = [];
        foreach ($request->array('fields') as $field) {
            if (!isset($field['name']) || !isset($field['type'])) {
                continue;
            }
            $normalize_fields[htmlspecialchars($field['name'], ENT_QUOTES, 'UTF-8')] = $field['type'];
        }

        $certificate->update([
            'name' => $request->string('name')->value(),
            'fields' => $normalize_fields,
        ]);

        return redirect()->back()->with('success', 'Die Urkunde wurde erfolgreich aktualisiert.');
    }

    /**
     * @codeCoverageIgnore
     * @param Certificate $certificate
     * @param ExportService $export_service
     * @return void
     */
    public function exportXlsx(Certificate $certificate, ExportService $export_service): void
    {
        $export_service->exportAndOutputToBrowser($certificate->name . '.tpl.xlsx', [
            'header' => array_keys($certificate->fields)
        ]);
    }

    public function destroy(Certificate $certificate)
    {
        if (file_exists($certificate->file_path)) {
            unlink($certificate->file_path);
        }

        if (!file_exists($certificate->file_path)) {
            $certificate->delete();
        }

        return redirect()->route('home')->with('success', 'Die Urkundenvorlage ' . $certificate->name . ' wurde erfolgreich gelöscht.');
    }
}

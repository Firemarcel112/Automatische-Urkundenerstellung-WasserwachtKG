<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Document;
use App\Services\PDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelReader;

class CertificateCreateController
{

    public function show(Request $request, Certificate $certificate, array $import_fields = [])
    {

        $tmp_import_fields = [];
        $original_fields = collect($certificate->fields);

        foreach ($import_fields as $index => $fields) {
            foreach ($fields as $name => $value) {
                $field_type = $original_fields[$name] ?? null;

                $import_field = [
                    'name' => $name,
                    'type' => $field_type,
                    'value' => $value,
                    'selected' => null,
                ];

                if ($field_type == 'button') {
                    $import_field['selected'] = strtolower((string) $value) == 'true' || strtolower((string) $value) == 'on';
                }

                $tmp_import_fields[$index][] = $import_field;
            }
        }

        return view('certificates.create.show', [
            'certificate' => $certificate,
            'import_fields' => $tmp_import_fields,
        ]);
    }

    public function store(Request $request, Certificate $certificate, PDFService $pdf_service)
    {
        $original_fields = $certificate->fields;
        $modified_fields = [];

        foreach ($request->array('certificate') as $key => $fields) {
            foreach ($fields as $field_name => $field_value) {
                if (!array_key_exists($field_name, $original_fields)) {
                    continue;
                }
                if ($field_value == 'true') {
                    $modified_fields[$key][$field_name] = 'On';
                } elseif ($field_value == 'false') {
                    $modified_fields[$key][$field_name] = 'Off';
                } else {
                    $modified_fields[$key][$field_name] = $field_value;
                }
            }
        }
        foreach ($modified_fields as $form_data) {
            $file_name = $certificate->name . '_' . uniqid();
            $file_path = Storage::disk('certificates_public')->path('/' . $file_name . '.pdf');
            $pdf_service->fillFields(
                $certificate->file_path,
                $form_data,
                $file_path,
            );

            if (file_exists($file_path)) {

                Document::create([
                    'file_path' => $file_path,
                    'file_name' => $file_name,
                    'certificate_template_name' => $certificate->name,
                    'hash' => hash_file('sha256', $file_path),
                ]);
            }
        }

        return redirect()->route('documents.index')
            ->with('success', 'Urkunden wurden erfolgreich erstellt!');
    }

    public function importXlsx(Request $request, Certificate $certificate)
    {
        $import_file = $request->file('import_file');
        $file = SimpleExcelReader::create($import_file->getRealPath(), 'xlsx')->getRows();

        Session::push('success', 'Du hast die Datei erfolgreich importiert, prüfe unten dein Ergebnis und klicke anschließend auf Urkunden erstellen');
        return $this->show($request, $certificate, $file->all());
    }
}

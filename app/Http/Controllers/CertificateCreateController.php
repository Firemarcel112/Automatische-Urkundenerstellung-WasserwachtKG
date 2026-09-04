<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Document;
use App\Services\PDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateCreateController
{

    public function show(Request $request, Certificate $certificate)
    {

        return view('certificates.create.show', [
            'certificate' => $certificate,
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
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificate;
use Illuminate\Support\Facades\Storage;
use App\Services\PDFService;

class CertificateUploadController extends Controller
{

    public function store(Request $request, PDFService $pdf_service)
    {
        $request->file('certificate')
            ->storeAs('certificates', $request->string('name') . '.pdf');

        $file_path = Storage::disk('certificates_private')->path($request->string('name') . '.pdf');

        $pdf_fields = $pdf_service->getFields($file_path);

        $certificate = Certificate::create(
            [
                'name' => $request->string('name'),
                'fields' => json_encode($pdf_fields),
                'file_path' => $file_path,
            ]
        );

        return redirect()->route('certificate.show', ['certificate' => $certificate->id]);
    }
}

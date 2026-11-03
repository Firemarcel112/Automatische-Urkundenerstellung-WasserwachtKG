<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Services\PDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateUploadController extends Controller
{

    public function store(Request $request, PDFService $pdf_service)
    {
        $file_name = str($request->file('certificate')->getClientOriginalName())
            ->remove('.' . $request->file('certificate')->getClientOriginalExtension())
            ->value();

        $name = empty($request->string('name')->value()) ? $file_name : $request->string('name')->value();

        $request->file('certificate')
            ->storeAs('certificates', $name . '.pdf');

        $file_path = Storage::disk('certificates_private')->path($name . '.pdf');

        $pdf_fields = $pdf_service->getFields($file_path);

        $certificate = Certificate::create(
            [
                'name' => $name,
                'fields' => $pdf_fields,
                'file_path' => $file_path,
            ]
        );

        return redirect()->route('certificates.settings.show', ['certificate' => $certificate]);
    }
}

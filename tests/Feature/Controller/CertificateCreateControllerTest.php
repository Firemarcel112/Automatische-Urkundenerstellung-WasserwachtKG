<?php

namespace Tests\Feature\Controller;

use App\Models\Certificate;
use App\Services\ImportService;
use App\Services\PDFService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;

class CertificateCreateControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function testCertificateShowIsReachable()
    {
        $certificate = Certificate::create([
            'name' => uniqid(),
            'fields' => [
                'Test' => 'Button',
                'Test_1' => 'Text',
            ],
            'file_path' => '/var/www/html/test.pdf',
        ]);

        $response = $this->get(route('certificates.create.show', [
            'certificate' => $certificate
        ]));

        $response->assertViewHas('certificate', $certificate);
    }

    public function testCertificateShowPassesImportFieldsCorrectly()
    {
        $certificate = Certificate::create([
            'name' => uniqid(),
            'fields' => [
                'Test' => 'button',
                'Test_1' => 'Text',
            ],
            'file_path' => '/var/www/html/test.pdf',
        ]);

        $import_fields = [[
            'Test' => 'on',
            'Test_1' => 'Text',
        ]];

        session()->flash('import_fields', $import_fields);

        $response = $this->get(route('certificates.create.show', [
            'certificate' => $certificate
        ]));

        $response->assertSessionHas('import_fields', $import_fields);
        $response->assertViewHas('import_fields', [
            [
                [
                    'name' => 'Test',
                    'type' => 'button',
                    'value' => 'on',
                    'selected' => true,
                ],
                [
                    'name' => 'Test_1',
                    'type' => 'Text',
                    'value' => 'Text',
                    'selected' => null,
                ],
            ]
        ]);
    }

    public function testImportXlsxRedirectsCorrectly()
    {

        $certificate = Certificate::create([
            'name' => uniqid(),
            'fields' => [
                'Test' => 'button',
                'Test_1' => 'Text',
            ],
            'file_path' => '/var/www/html/test.pdf',
        ]);

        $import_fields = [
            [
                'name' => 'Test',
                'type' => 'button',
                'value' => 'on',
            ],
            [
                'name' => 'Test_1',
                'type' => 'Text',
                'value' => 'Text',
            ],
        ];

        $this->mock(ImportService::class, function (MockInterface $mock) use ($import_fields) {
            $mock->expects('importXlsxAndReturnFieldsWithName')
                ->once()
                ->andReturn($import_fields);
        });

        $file = UploadedFile::fake()->create('test.xlsx', 100, 'application/pdf');

        $response = $this->post(route('certificates.create.import.xlsx', [
            'certificate' => $certificate,
        ]), [
            'import_file' => $file,
        ]);

        $response->assertSessionHas('import_fields', $import_fields);
    }

    public function testStoresCertificateCorrectly()
    {
        Storage::fake('certificates_public');

        $this->mock(PDFService::class, function (MockInterface $mock) {
            $mock->expects('fillFields')
                ->times(3)
                ->andReturnUsing(function ($certificate_path, $form_data, $file_path) {
                    file_put_contents($file_path, '%PDF-1.4 mock content');
                    return true;
                });
        });

        $certificate = Certificate::create([
            'name' => uniqid(),
            'fields' => [
                'Test' => 'button',
                'Test_1' => 'Text',
            ],
            'file_path' => '/var/www/html/test.pdf',
        ]);

        $response = $this->post(route('certificates.create.store', [
            'certificate' => $certificate,
        ]),  [
            'certificate' => [
                [
                    'Test' => 'on',
                    'Test_1' => 'Hier könnte Ihre Werbung stehen',
                ],
                [
                    'Test' => 'true',
                    'Test_1' => 'Hier könnte Ihre Werbung stehen',
                ],
                [
                    'Test' => 'false',
                    'Test_1' => 'Hier könnte Ihre Werbung stehen',
                    'unknown-field' => 'Dieses Feld gibt es nicht im Original',
                ]
            ]
        ]);

        $response->assertRedirect(route('documents.index'));
        $this->assertDatabaseCount('documents', 3);
        $response->assertSessionHas('success', 'Urkunden wurden erfolgreich erstellt!');
    }
}

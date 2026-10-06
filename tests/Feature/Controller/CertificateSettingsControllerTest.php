<?php

namespace Tests\Feature\Controller;

use App\Models\Certificate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CertificateSettingsControllerTest extends TestCase
{
    use RefreshDatabase;


    public function testShowSettingsPage()
    {
        $certificate = Certificate::create([
            'name' => uniqid(),
            'fields' => [
                'Test' => 'Button',
                'Test_1' => 'Text',
            ],
            'file_path' => '/var/www/html/test.pdf',
        ]);

        $response = $this->get(route('certificates.settings.show', [
            'certificate' => $certificate,
        ]));

        $response->assertOk();
        $response->assertViewHas([
            'certificate' => $certificate,
            'field_types'
        ]);
    }

    public function testreturnsBackWithErrorWhenCertificateFileDoesNotExist()
    {
        $certificate = Certificate::create([
            'name' => uniqid(),
            'fields' => [
                'Test' => 'Button',
                'Test_1' => 'Text',
            ],
            'file_path' => '/var/www/html/' . uniqid() . '.pdf',
        ]);

        $response = $this->get(route('certificates.settings.showDocument', [
            'certificate' => $certificate,
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Die Urkundenvorlage existiert nicht mehr.');
    }

    public function testShowDocument()
    {
        $certificate = Certificate::create([
            'name' => uniqid(),
            'fields' => [
                'Test' => 'Button',
                'Test_1' => 'Text',
            ],
            'file_path' => '/var/www/html/test23.pdf',
        ]);

        $temp_file = touch($certificate->file_path);
        file_put_contents($temp_file, 'PDF Content');

        try {
            $response = $this->get(route('certificates.settings.showDocument', [
                'certificate' => $certificate,
            ]));

            $response->assertHeader('Content-Type', 'application/pdf');
        } finally {
            if (file_exists($temp_file)) {
                @unlink($temp_file);
            }
        }
    }

    public function testUpdateNameOfCertificate()
    {
        $certificate = Certificate::create([
            'name' => uniqid(),
            'fields' => [
                'Test' => 'Button',
                'Test_1' => 'Text',
            ],
            'file_path' => '/var/www/html/test23.pdf',
        ]);

        $response = $this->post(route('certificates.settings.update', [
            'certificate' => $certificate,
        ]), [
            'name' => 'Updated Name',
        ]);

        $response->assertRedirectBack();
        $response->assertsessionHas('success', 'Die Urkunde wurde erfolgreich aktualisiert.');
        $this->assertDatabaseHas('certificates', [
            'certificate_id' => $certificate->certificate_id,
            'name' => 'Updated Name',
        ]);
    }

    public function testUpdateFieldsOfCertificate()
    {
        $certificate_name = "Test Certificate";
        $certificate = Certificate::create([
            'name' => $certificate_name,
            'fields' => [
                'Test' => 'Button',
                'Test_1' => 'Text',
            ],
            'file_path' => '/var/www/html/test23.pdf',
        ]);

        $response = $this->post(route('certificates.settings.update', [
            'certificate' => $certificate,
        ]), [
            'fields' => [
                [
                    'name' => 'Updated Field',
                    'type' => 'Text',
                ],
            ],
        ]);

        $updated_certificate = $certificate->fresh();

        $response->assertRedirectBack();
        $response->assertsessionHas('success', 'Die Urkunde wurde erfolgreich aktualisiert.');
        $this->assertArrayHasKey('Updated Field', $updated_certificate->fields);
    }

    public function testContinueWhenNameOrTypeIsMissingOnUpdate()
    {
        $certificate_name = "Test Certificate";
        $certificate = Certificate::create([
            'name' => $certificate_name,
            'fields' => [
                'Test' => 'Button',
                'Test_1' => 'Text',
            ],
            'file_path' => '/var/www/html/test23.pdf',
        ]);

        $response = $this->post(route('certificates.settings.update', [
            'certificate' => $certificate,
        ]), [
            'fields' => [
                [
                    'xy' => 'Updated Field',
                    'te' => 'Text',
                ],
            ],
        ]);

        $response->assertRedirectBack();
        $response->assertSessionHas('success', 'Die Urkunde wurde erfolgreich aktualisiert.');

        $updated_certificate = $certificate->fresh();

        $this->assertArrayNotHasKey('xy', $updated_certificate->fields);
    }

    public function testDeleteDocument()
    {
        $certificate = Certificate::create([
            'name' => uniqid(),
            'fields' => [
                'Test' => 'Button',
                'Test_1' => 'Text',
            ],
            'file_path' => '/var/www/html/' . uniqid() . '.pdf',
        ]);

        $temp_file = touch($certificate->file_path);
        file_put_contents($temp_file, 'PDF Content');

        try {
            $response = $this->delete(route('certificates.settings.delete', [
                'certificate' => $certificate,
            ]));

            $response->assertRedirect(route('home'));
            $response->assertSessionHas('success', 'Die Urkundenvorlage ' . $certificate->name . ' wurde erfolgreich gelöscht.');
            $this->assertModelMissing($certificate);
        } finally {
            if (file_exists($temp_file)) {
                @unlink($temp_file);
            }
        }
    }
}

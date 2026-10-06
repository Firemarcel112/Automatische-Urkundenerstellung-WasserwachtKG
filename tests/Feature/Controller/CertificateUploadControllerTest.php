<?php

namespace Tests\Feature\Controller;

use App\Services\PDFService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery\MockInterface;
use Tests\TestCase;

class CertificateUploadControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCertificateStoresSuccessfully()
    {
        Storage::fake('public');
        Storage::fake('certificates_private');

        $this->mock(PDFService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getFields')
                ->once()
                ->andReturn([
                    'Test' => 'Text',
                    'Test2' => 'Button',
                ]);
        });

        $file = UploadedFile::fake()->create('test.pdf', 100, 'application/pdf');

        $name = 'Test Zertifikat-' . uniqid();

        $response = $this->post(route('certificates.upload.store'), [
            'name' => $name,
            'certificate' => $file,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('certificates', [
            'name' => $name,
        ]);
    }
}

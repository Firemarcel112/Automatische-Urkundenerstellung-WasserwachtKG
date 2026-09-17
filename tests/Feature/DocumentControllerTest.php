<?php

namespace Tests\Feature;

use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSiteIsReachable(): void
    {
        $response = $this->get(route('documents.index'));

        $response->assertStatus(200);
    }

    public function testSiteIsReachableWithData(): void
    {

        $documents_data = Document::create([
            'certificate_template_name' => 'Test 1',
            'file_name' => uniqid() . '.pdf',
            'file_path' => '/var/www/test' . uniqid() . '.pdf',
            'hash' => 'xy',
        ]);

        $response = $this->get(route('documents.index'));

        $response->assertViewHas('documents', function ($documents) use ($documents_data) {
            return $documents->contains($documents_data);
        });
    }

    public function testShowDocument()
    {
        $temp_file = tempnam(sys_get_temp_dir(), 'test_pdf');
        file_put_contents($temp_file, 'PDF Content');

        try {
            $documents_data = Document::create([
                'certificate_template_name' => 'Test 1',
                'file_name' => uniqid() . '.pdf',
                'file_path' => $temp_file,
                'hash' => 'xy',
            ]);

            $document_id = $documents_data->document_id;
            $document_hash = $documents_data->hash;

            $response = $this->get(route('documents.show', [
                'document' => $document_id,
                'hash' => $document_hash
            ]));

            $response->assertOk();
        } finally {
            if (file_exists($temp_file)) {
                @unlink($temp_file);
            }
        }
    }

    public function testDocumentShowReturnsErrorWithWrongHash()
    {

        $documents_data = Document::create([
            'certificate_template_name' => 'Test 1',
            'file_name' => uniqid() . '.pdf',
            'file_path' => '/var/www/test',
            'hash' => 'xy',
        ]);

        $document_id = $documents_data->document_id;
        $document_hash = 'wrong-hash';

        $response = $this->get(route('documents.show', [
            'document' => $document_id,
            'hash' => $document_hash
        ]));

        $response->assertRedirect(route('documents.index'));
        $response->assertSessionHas('error');
    }

    public function testDownloadDocument()
    {
        $temp_file = tempnam(sys_get_temp_dir(), 'test_pdf');
        file_put_contents($temp_file, 'PDF Content');

        try {
            $file_name = uniqid() . '.pdf';
            $documents_data = Document::create([
                'certificate_template_name' => 'Test 1',
                'file_name' => $file_name,
                'file_path' => $temp_file,
                'hash' => 'xy',
            ]);

            $document_id = $documents_data->document_id;
            $document_hash = $documents_data->hash;

            $response = $this->get(route('documents.download', [
                'document' => $document_id,
                'hash' => $document_hash
            ]));

            $response->assertdownload($file_name);
        } finally {
            if (file_exists($temp_file)) {
                @unlink($temp_file);
            }
        }
    }

    public function testDocumentReturnsErrorWhenWrongHashInDownload()
    {
        $documents_data = Document::create([
            'certificate_template_name' => 'Test 1',
            'file_name' => uniqid() . '.pdf',
            'file_path' => '/var/www/test',
            'hash' => 'xy',
        ]);

        $document_id = $documents_data->document_id;

        $response = $this->get(route('documents.download', [
            'document' => $document_id,
            'hash' => 'wrong-hash'
        ]));

        $response->assertRedirect(route('documents.index'));
        $response->assertSessionHas('error');
    }

    public function testDeleteDocument()
    {
        $temp_file = tempnam(sys_get_temp_dir(), 'test_pdf');
        file_put_contents($temp_file, 'PDF Content');

        try {
            $file_name = uniqid() . '.pdf';
            $documents_data = Document::create([
                'certificate_template_name' => 'Test 1',
                'file_name' => $file_name,
                'file_path' => $temp_file,
                'hash' => 'xy',
            ]);

            $document_id = $documents_data->document_id;
            $document_hash = $documents_data->hash;

            $response = $this->delete(route('documents.destroy', [
                'document' => $document_id,
            ]));

            $this->assertModelMissing($documents_data);
            $this->assertFileDoesNotExist($temp_file);
            $response->assertSessionHas('success');
        } finally {
            if (file_exists($temp_file)) {
                @unlink($temp_file);
            }
        }
    }
}

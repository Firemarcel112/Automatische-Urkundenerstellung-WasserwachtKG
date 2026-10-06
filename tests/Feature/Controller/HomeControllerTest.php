<?php

namespace Tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use App\Models\Certificate;
use Tests\TestCase;


class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function testSiteIsReachable(): void
    {
        $response = $this->get(route('home'));

        $response->assertViewHas('certificate_templates');
        $response->assertStatus(200);
    }

    public function testSiteIsReachableWithData(): void
    {
        $fake_file_1 = UploadedFile::fake()->create(
            'certificate.pdf',
            1024 * 10,
            'application/pdf'
        );

        $fake_file_2 = UploadedFile::fake()->create(
            'certificate2.pdf',
            1024 * 10,
            'application/pdf'
        );

        $certificate_1 = Certificate::create([
            'name' => 'Test 1',
            'fields' => [
                ['Test' => 'Text']
            ],
            'file_path' => $fake_file_1->getRealPath(),
        ]);

        $certificate_2 = Certificate::create([
            'name' => 'Test 2',
            'fields' => [
                ['Test' => 'Text']
            ],
            'file_path' => $fake_file_2->getRealPath(),
        ]);

        $response = $this->get(route('home'));

        $response->assertViewHas('certificate_templates', function ($certificates) use ($certificate_1, $certificate_2) {
            return $certificates->contains($certificate_1)
                && $certificates->contains($certificate_2);
        });
        $response->assertStatus(200);
    }
}

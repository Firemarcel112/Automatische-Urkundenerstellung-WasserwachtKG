<?php

namespace Tests\Feature\Services;

use App\Services\ExportService;
use Mockery\MockInterface;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Tests\TestCase;

class ExportServiceTest extends TestCase
{
    public function testExportAndOutputsToBrowserWithHeader()
    {
        $writer_mock = $this->mock(SimpleExcelWriter::class, function (MockInterface $mock) {
            $mock->shouldReceive('addHeader')
                ->once()
                ->with(['ID', 'Name']);

            $mock->shouldReceive('toBrowser')
                ->once();
        });

        /**
         * @var MockInterface|ExportService $service
         */
        $service = $this->partialMock(ExportService::class, function (MockInterface $mock) use ($writer_mock) {
            $mock->shouldReceive('createWriter')
                ->once()
                ->with('mein_export.xlsx')
                ->andReturn($writer_mock);
        });

        $options = [
            [
                'type' => 'header',
                'header' => ['ID', 'Name']
            ]
        ];

        $service->exportAndOutputToBrowser('mein_export.xlsx', $options);

        $this->assertTrue(true);
    }
}

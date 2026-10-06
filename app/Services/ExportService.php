<?php

namespace App\Services;

use Spatie\SimpleExcel\SimpleExcelWriter;

class ExportService
{
    /**
     * Exportiert Daten in csv,xlsx und gibt sie direkt an den Browser aus
     * @param string $file_name
     * @param array $options
     * @return void
     */
    public function exportAndOutputToBrowser(string $file_name, array $options = [])
    {
        $csv = $this->createWriter($file_name);

        foreach ($options as $option) {
            if (isset($option['type']) && $option['type'] === 'header') {
                $csv->addHeader($option['header']);
            }
        }

        $csv->toBrowser();
    }

    /**
     * Erstellt den Writer
     * @param string $path
     * @return SimpleExcelWriter
     * @codeCoverageIgnore
     */
    public function createWriter(string $path): SimpleExcelWriter
    {
        return SimpleExcelWriter::streamDownload($path);
    }
}

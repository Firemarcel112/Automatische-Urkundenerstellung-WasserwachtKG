<?php

namespace App\Services;

use Spatie\SimpleExcel\SimpleExcelReader;

class ImportService
{

    /**
     * Importiert die Excel und gibt die Felder mit Namen zurück
     * @param string $file_path
     * @return array<mixed>
     */
    public function importXlsxAndReturnFieldsWithName(string $file_path): array
    {
        return SimpleExcelReader::create($file_path, 'xlsx')
            ->getRows()
            ->all();
    }
}

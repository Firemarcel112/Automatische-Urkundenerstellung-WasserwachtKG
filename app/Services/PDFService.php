<?php

namespace App\Services;

use mikehaertl\pdftk\Pdf;

class PDFService
{

    /**
     * Gibt die Felder als Array mit Name und Typ zurück
     * @param string $file_path
     * @param array{FieldName: array<string>, FieldType: array<string>} $ignore_fields
     * @return array<string, string>
     */
    public function getFields(string $file_path, array $ignore_fields = []): array
    {
        $pdf = $this->getPdf($file_path);

        $all_fields = [];

        $pdf_fields = $pdf->getDataFields()->__toArray();

        foreach ($pdf_fields as $field) {
            foreach ($ignore_fields as $key => $ignore_field) {
                if (in_array($field[$key], $ignore_field)) {
                    continue 2;
                }
            }
            $all_fields[$field['FieldName']] = $field['FieldType'];
        }
        return $all_fields;
    }

    /**
     * Befüllt die Übergebenen Formularfelder und speichert die Urkunde
     *
     * @param string $file_path
     * @param array $fields
     * @param string $output_path
     * @return bool
     */
    public function fillFields(string $file_path, array $fields, string $output_path): bool
    {
        $pdf = $this->getPdf($file_path);

        return $pdf->fillForm($fields)
            ->needAppearances()
            ->saveAs($output_path);
    }

    /**
     * Lädt die PDF
     *
     * @param string $file_path
     * @return Pdf
     */
    protected function getPdf(string $file_path): Pdf
    {
        $options = [];

        if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
            $options['command'] = config('app.pdf_path');
        }

        return new Pdf($file_path, $options);
    }
}

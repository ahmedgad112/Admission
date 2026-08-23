<?php

namespace App\Support;

use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class SimpleXlsx
{
    /**
     * @param  list<string>  $headers
     * @param  list<list<string|int|float|null>>  $rows
     */
    public function download(string $filename, string $sheetName, array $headers, array $rows): StreamedResponse
    {
        $binary = $this->binary($sheetName, $headers, $rows);

        return response()->streamDownload(function () use ($binary): void {
            echo $binary;
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * @param  list<string>  $headers
     * @param  list<list<string|int|float|null>>  $rows
     */
    public function binary(string $sheetName, array $headers, array $rows): string
    {
        $path = tempnam(sys_get_temp_dir(), 'xlsx');
        abort_unless(is_string($path), 500, 'Unable to create the Excel file.');

        $zip = new ZipArchive;

        if ($zip->open($path, ZipArchive::OVERWRITE) !== true) {
            unlink($path);
            abort(500, 'Unable to create the Excel file.');
        }

        $zip->addFromString('[Content_Types].xml', $this->contentTypes());
        $zip->addFromString('_rels/.rels', $this->packageRelationships());
        $zip->addFromString('xl/workbook.xml', $this->workbook($sheetName));
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->workbookRelationships());
        $zip->addFromString('xl/worksheets/sheet1.xml', $this->worksheet($headers, $rows));
        $zip->close();

        $binary = file_get_contents($path);
        unlink($path);

        abort_unless(is_string($binary), 500, 'Unable to create the Excel file.');

        return $binary;
    }

    private function contentTypes(): string
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
</Types>
XML;
    }

    private function packageRelationships(): string
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>
XML;
    }

    private function workbook(string $sheetName): string
    {
        $name = htmlspecialchars($this->safeSheetName($sheetName), ENT_XML1 | ENT_QUOTES, 'UTF-8');

        return <<<XML
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets>
    <sheet name="{$name}" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>
XML;
    }

    private function workbookRelationships(): string
    {
        return <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
</Relationships>
XML;
    }

    /**
     * @param  list<string>  $headers
     * @param  list<list<string|int|float|null>>  $rows
     */
    private function worksheet(array $headers, array $rows): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>';
        $xml .= $this->row(1, $headers);

        foreach ($rows as $index => $row) {
            $xml .= $this->row($index + 2, $row);
        }

        return $xml.'</sheetData></worksheet>';
    }

    /**
     * @param  list<string|int|float|null>  $values
     */
    private function row(int $number, array $values): string
    {
        $cells = '';

        foreach (array_values($values) as $index => $value) {
            $ref = $this->columnLetter($index).$number;
            $text = htmlspecialchars((string) ($value ?? ''), ENT_XML1 | ENT_QUOTES, 'UTF-8');
            $cells .= '<c r="'.$ref.'" t="inlineStr"><is><t>'.$text.'</t></is></c>';
        }

        return '<row r="'.$number.'">'.$cells.'</row>';
    }

    private function columnLetter(int $index): string
    {
        $letter = '';
        $index++;

        while ($index > 0) {
            $index--;
            $letter = chr(65 + ($index % 26)).$letter;
            $index = intdiv($index, 26);
        }

        return $letter;
    }

    private function safeSheetName(string $name): string
    {
        $safe = trim(str_replace(['\\', '/', '*', '?', ':', '[', ']'], '', $name));

        if ($safe === '') {
            return 'Sheet1';
        }

        return substr($safe, 0, 31);
    }
}

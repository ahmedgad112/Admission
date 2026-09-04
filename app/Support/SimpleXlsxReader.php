<?php

namespace App\Support;

use ZipArchive;

class SimpleXlsxReader
{
    /**
     * @return list<list<string>>
     */
    public function rows(string $path): array
    {
        $zip = new ZipArchive;

        if ($zip->open($path) !== true) {
            return [];
        }

        $shared = $this->sharedStrings($zip);
        $sheet = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if (! is_string($sheet) || $sheet === '') {
            return [];
        }

        return $this->parseSheet($sheet, $shared);
    }

    /**
     * @return list<string>
     */
    private function sharedStrings(ZipArchive $zip): array
    {
        $xml = $zip->getFromName('xl/sharedStrings.xml');

        if (! is_string($xml) || $xml === '') {
            return [];
        }

        $strings = [];

        if (preg_match_all('/<si\b[^>]*>(.*?)<\/si>/s', $xml, $items) === 0) {
            return [];
        }

        foreach ($items[1] as $item) {
            if (preg_match_all('/<t[^>]*>(.*?)<\/t>/s', $item, $texts) === 0) {
                $strings[] = '';

                continue;
            }

            $strings[] = html_entity_decode(implode('', $texts[1]), ENT_XML1 | ENT_QUOTES, 'UTF-8');
        }

        return $strings;
    }

    /**
     * @param  list<string>  $shared
     * @return list<list<string>>
     */
    private function parseSheet(string $sheet, array $shared): array
    {
        $rows = [];

        if (preg_match_all('/<row\b[^>]*>(.*?)<\/row>/s', $sheet, $rowMatches) === 0) {
            return [];
        }

        foreach ($rowMatches[1] as $rowXml) {
            $cells = [];

            if (preg_match_all('/<c\b([^>]*)>(.*?)<\/c>/s', $rowXml, $cellMatches, PREG_SET_ORDER) === 0) {
                $rows[] = [];

                continue;
            }

            foreach ($cellMatches as $cell) {
                $attributes = $cell[1];
                $inner = $cell[2];
                $index = 0;

                if (preg_match('/r="([A-Z]+)\d+"/', $attributes, $reference) === 1) {
                    $index = $this->columnIndex($reference[1]);
                }

                $type = preg_match('/t="([^"]+)"/', $attributes, $typeMatch) === 1
                    ? $typeMatch[1]
                    : 'n';

                $value = $this->cellValue($type, $inner, $shared);

                $cells[$index] = $value;
            }

            if ($cells === []) {
                $rows[] = [];

                continue;
            }

            $width = max(array_keys($cells)) + 1;
            $row = array_fill(0, $width, '');

            foreach ($cells as $index => $value) {
                $row[$index] = $value;
            }

            $rows[] = array_values($row);
        }

        return $rows;
    }

    /**
     * @param  list<string>  $shared
     */
    private function cellValue(string $type, string $inner, array $shared): string
    {
        if ($type === 'inlineStr') {
            if (preg_match_all('/<t[^>]*>(.*?)<\/t>/s', $inner, $texts) === 0) {
                return '';
            }

            return html_entity_decode(implode('', $texts[1]), ENT_XML1 | ENT_QUOTES, 'UTF-8');
        }

        $raw = preg_match('/<v>(.*?)<\/v>/s', $inner, $match) === 1
            ? html_entity_decode($match[1], ENT_XML1 | ENT_QUOTES, 'UTF-8')
            : '';

        if ($type === 's') {
            $index = (int) $raw;

            return $shared[$index] ?? '';
        }

        return $raw;
    }

    private function columnIndex(string $letters): int
    {
        $index = 0;

        foreach (str_split($letters) as $letter) {
            $index = ($index * 26) + (ord($letter) - 64);
        }

        return $index - 1;
    }
}

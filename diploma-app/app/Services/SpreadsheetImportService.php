<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use RuntimeException;
use SimpleXMLElement;
use ZipArchive;

class SpreadsheetImportService
{
    private const XLSX_NS = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';

    public function import(UploadedFile $file): array
    {
        return $this->importFromPath(
            $file->getRealPath(),
            strtolower($file->getClientOriginalExtension())
        );
    }

    public function importStoredFile(string $path, ?string $originalName = null): array
    {
        return $this->importFromPath(
            $path,
            strtolower(pathinfo($originalName ?: $path, PATHINFO_EXTENSION))
        );
    }

    private function importFromPath(string $path, string $extension): array
    {
        return match ($extension) {
            'csv', 'txt' => $this->importCsv($path),
            'xlsx' => $this->importXlsx($path),
            default => throw new RuntimeException('Поддерживаются только CSV и XLSX файлы.'),
        };
    }

    private function importCsv(string $path): array
    {
        $handle = fopen($path, 'rb');

        if (! $handle) {
            throw new RuntimeException('Не удалось открыть CSV-файл.');
        }

        $firstLine = fgets($handle) ?: '';
        rewind($handle);
        $delimiter = $this->detectDelimiter($firstLine);

        $rows = [];
        $rawHeader = fgetcsv($handle, 0, $delimiter) ?: [];
        $headers = $this->sanitizeHeaders($rawHeader);

        $rowIndex = 1;

        while (($values = fgetcsv($handle, 0, $delimiter)) !== false) {
            $values = array_pad($values, count($headers), '');
            $rows[] = [
                'row_index' => $rowIndex++,
                'payload' => array_combine($headers, array_map($this->normalizeCell(...), $values)),
            ];
        }

        fclose($handle);

        return [
            'headers' => $headers,
            'rows' => $rows,
        ];
    }

    private function importXlsx(string $path): array
    {
        if (! class_exists(ZipArchive::class)) {
            throw new RuntimeException('Для импорта XLSX в PHP должно быть включено расширение zip.');
        }

        $archive = new ZipArchive();

        if ($archive->open($path) !== true) {
            throw new RuntimeException('Не удалось открыть XLSX-файл.');
        }

        $sharedStrings = $this->readSharedStrings($archive);
        $sheetXml = $archive->getFromName('xl/worksheets/sheet1.xml');

        if (! $sheetXml) {
            $archive->close();
            throw new RuntimeException('В XLSX не найден первый лист.');
        }

        $rowsXml = new SimpleXMLElement($sheetXml);
        $sheetData = $rowsXml->children(self::XLSX_NS)->sheetData;
        $rows = [];
        $header = [];
        $rowIndex = 1;

        foreach ($sheetData->row as $row) {
            $cells = [];

            foreach ($row->children(self::XLSX_NS)->c as $cell) {
                $ref = (string) $cell['r'];
                $columnIndex = $this->columnReferenceToIndex($ref);
                $cells[$columnIndex] = $this->extractCellValue($cell, $sharedStrings);
            }

            if ($header === []) {
                ksort($cells);
                $header = $this->sanitizeHeaders(array_values($cells));
                continue;
            }

            $payload = [];

            foreach ($header as $index => $columnName) {
                $payload[$columnName] = $this->normalizeCell(Arr::get($cells, $index, ''));
            }

            $rows[] = [
                'row_index' => $rowIndex++,
                'payload' => $payload,
            ];
        }

        $archive->close();

        return [
            'headers' => $header,
            'rows' => $rows,
        ];
    }

    private function readSharedStrings(ZipArchive $archive): array
    {
        $xml = $archive->getFromName('xl/sharedStrings.xml');

        if (! $xml) {
            return [];
        }

        $sharedStringsXml = new SimpleXMLElement($xml);
        $items = $sharedStringsXml->children(self::XLSX_NS)->si;
        $result = [];

        foreach ($items as $item) {
            $children = $item->children(self::XLSX_NS);

            if (isset($children->t)) {
                $result[] = (string) $children->t;
                continue;
            }

            $parts = [];

            foreach ($children->r as $run) {
                $parts[] = (string) $run->children(self::XLSX_NS)->t;
            }

            $result[] = implode('', $parts);
        }

        return $result;
    }

    private function extractCellValue(SimpleXMLElement $cell, array $sharedStrings): string
    {
        $type = (string) $cell['t'];
        $children = $cell->children(self::XLSX_NS);

        return match ($type) {
            's' => (string) Arr::get($sharedStrings, (int) ($children->v ?? 0), ''),
            'inlineStr' => (string) ($children->is->children(self::XLSX_NS)->t ?? ''),
            default => (string) ($children->v ?? ''),
        };
    }

    private function columnReferenceToIndex(string $reference): int
    {
        preg_match('/[A-Z]+/i', $reference, $matches);
        $letters = strtoupper($matches[0] ?? 'A');
        $index = 0;

        foreach (str_split($letters) as $letter) {
            $index = ($index * 26) + (ord($letter) - 64);
        }

        return max(0, $index - 1);
    }

    private function sanitizeHeaders(array $headers): array
    {
        $result = [];

        foreach ($headers as $index => $header) {
            $clean = trim((string) $header);
            $result[] = $clean !== '' ? $clean : 'Column '.($index + 1);
        }

        return $result;
    }

    private function detectDelimiter(string $line): string
    {
        $delimiters = [',', ';', "\t"];
        $bestDelimiter = ',';
        $bestScore = 0;

        foreach ($delimiters as $delimiter) {
            $score = count(str_getcsv($line, $delimiter));

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestDelimiter = $delimiter;
            }
        }

        return $bestDelimiter;
    }

    private function normalizeCell(string $value): string
    {
        return trim(Str::of($value)->replace("\xEF\xBB\xBF", '')->value());
    }
}

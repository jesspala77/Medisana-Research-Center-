<?php

namespace App\Services\Transactions\Importers;

use App\Models\FinancialTransaction;
use App\Models\TransactionImportBatch;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use RuntimeException;

class CsvTransactionImporter
{
    public function import(UploadedFile|string $file, array $mapping = [], string $source = 'csv', ?int $userId = null): TransactionImportBatch
    {
        $path = $file instanceof UploadedFile ? $file->getRealPath() : $file;
        $filename = $file instanceof UploadedFile ? $file->getClientOriginalName() : basename($file);

        if (!$path || !is_readable($path)) {
            throw new RuntimeException('CSV file is not readable.');
        }

        $batch = TransactionImportBatch::create([
            'source' => $source,
            'filename' => $filename,
            'status' => 'processing',
            'mapping' => $mapping,
            'imported_by' => $userId,
            'started_at' => now(),
        ]);

        $handle = fopen($path, 'rb');
        $headers = fgetcsv($handle);
        if (!$headers) {
            throw new RuntimeException('CSV does not contain a header row.');
        }

        $headers = array_map(fn ($h) => Str::lower(trim((string) $h)), $headers);
        $resolved = $this->resolveMapping($headers, $mapping);
        $errors = [];
        $total = $imported = $skipped = $failed = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $total++;
            try {
                $record = array_combine($headers, array_pad($row, count($headers), null));
                $externalId = $this->value($record, $resolved['external_id'])
                    ?: hash('sha256', implode('|', [$source, $filename, $total, json_encode($record)]));

                $dateValue = $this->value($record, $resolved['date']);
                $amountValue = $this->value($record, $resolved['amount']);
                if (!$dateValue || $amountValue === null || $amountValue === '') {
                    $skipped++;
                    continue;
                }

                $amount = $this->parseAmount($amountValue);
                $transaction = FinancialTransaction::firstOrNew([
                    'source' => $source,
                    'external_id' => $externalId,
                ]);

                if ($transaction->exists) {
                    $skipped++;
                    continue;
                }

                $transaction->fill([
                    'transaction_date' => Carbon::parse($dateValue)->toDateString(),
                    'merchant' => $this->value($record, $resolved['merchant']),
                    'description' => $this->value($record, $resolved['description']),
                    'amount' => abs($amount),
                    'original_category' => $this->value($record, $resolved['category']),
                    'classification_status' => 'pending',
                    'raw_payload' => $record,
                ])->save();
                $imported++;
            } catch (\Throwable $e) {
                $failed++;
                if (count($errors) < 50) {
                    $errors[] = ['row' => $total + 1, 'error' => $e->getMessage()];
                }
            }
        }
        fclose($handle);

        $batch->update([
            'status' => $failed > 0 ? 'completed_with_errors' : 'completed',
            'rows_total' => $total,
            'rows_imported' => $imported,
            'rows_skipped' => $skipped,
            'rows_failed' => $failed,
            'errors' => $errors,
            'completed_at' => now(),
        ]);

        return $batch->refresh();
    }

    private function resolveMapping(array $headers, array $mapping): array
    {
        $aliases = [
            'date' => ['date', 'transaction date', 'expense date'],
            'merchant' => ['merchant', 'vendor', 'supplier', 'payee'],
            'description' => ['description', 'memo', 'notes', 'details'],
            'amount' => ['amount', 'total', 'expense amount', 'debit'],
            'category' => ['category', 'expense category', 'account'],
            'external_id' => ['id', 'transaction id', 'expense id', 'reference'],
        ];

        $resolved = [];
        foreach ($aliases as $field => $options) {
            $requested = isset($mapping[$field]) ? Str::lower(trim($mapping[$field])) : null;
            $resolved[$field] = $requested && in_array($requested, $headers, true)
                ? $requested
                : collect($options)->first(fn ($option) => in_array($option, $headers, true));
        }

        if (!$resolved['date'] || !$resolved['amount']) {
            throw new RuntimeException('Could not detect required date and amount columns. Supply a column mapping.');
        }

        return $resolved;
    }

    private function value(array $record, ?string $key): mixed
    {
        return $key ? ($record[$key] ?? null) : null;
    }

    private function parseAmount(mixed $value): float
    {
        $clean = preg_replace('/[^0-9.\-()]/', '', (string) $value);
        if (str_contains($clean, '(') && str_contains($clean, ')')) {
            $clean = '-'.str_replace(['(', ')'], '', $clean);
        }
        return (float) $clean;
    }
}

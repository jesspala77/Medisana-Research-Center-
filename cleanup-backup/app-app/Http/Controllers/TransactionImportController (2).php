<?php

namespace App\Http\Controllers;

use App\Jobs\ClassifyFinancialTransactionJob;
use App\Models\FinancialTransaction;
use App\Services\Transactions\Importers\CsvTransactionImporter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionImportController extends Controller
{
    public function store(Request $request, CsvTransactionImporter $importer): JsonResponse
    {
        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:20480'],
            'source' => ['nullable', 'string', 'max:50'],
            'mapping' => ['nullable', 'array'],
        ]);

        $batch = $importer->import(
            $request->file('file'),
            $validated['mapping'] ?? [],
            $validated['source'] ?? 'csv',
            optional($request->user())->id,
        );

        FinancialTransaction::query()
            ->where('source', $validated['source'] ?? 'csv')
            ->where('classification_status', 'pending')
            ->pluck('id')
            ->each(fn ($id) => ClassifyFinancialTransactionJob::dispatch($id));

        return response()->json($batch, 201);
    }
}

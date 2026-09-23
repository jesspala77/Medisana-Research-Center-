<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use App\Services\Transactions\TransactionClassifierService;
use Illuminate\Http\Request;

class TransactionClassificationController extends Controller
{
    public function index()
    {
        return FinancialTransaction::query()
            ->with('project')
            ->whereIn('classification_status', ['needs_review', 'unclassified'])
            ->orderByDesc('transaction_date')
            ->paginate(50);
    }

    public function classify(FinancialTransaction $transaction, TransactionClassifierService $service)
    {
        return response()->json($service->apply($transaction));
    }

    public function confirm(Request $request, FinancialTransaction $transaction)
    {
        $data = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'construction_project_id' => ['nullable', 'exists:construction_projects,id'],
            'note' => ['nullable', 'string'],
        ]);

        $transaction->update([
            'suggested_category' => $data['category'],
            'construction_project_id' => $data['construction_project_id'] ?? null,
            'classification_status' => 'confirmed',
            'reviewed_at' => now(),
            'reviewed_by' => $request->user()?->id,
        ]);

        $transaction->classificationFeedback()->create([
            'predicted_category' => $transaction->getOriginal('suggested_category'),
            'confirmed_category' => $data['category'],
            'confirmed_project_id' => $data['construction_project_id'] ?? null,
            'reviewer_note' => $data['note'] ?? null,
            'reviewed_by' => $request->user()?->id,
        ]);

        return response()->json($transaction->refresh());
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use App\Models\TransactionClassificationFeedback;
use App\Services\Transactions\ProjectCostPostingService;
use App\Services\Transactions\TransactionClassificationEngine;
use App\Services\Transactions\VendorMemoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionReviewController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $transactions = FinancialTransaction::query()
            ->when($request->filled('status'), fn ($q) => $q->where('classification_status', $request->string('status')),
                fn ($q) => $q->whereIn('classification_status', ['needs_review', 'unclassified']))
            ->latest('transaction_date')
            ->paginate(50);

        return $request->expectsJson()
            ? response()->json($transactions)
            : view('transactions.review', compact('transactions'));
    }

    public function reclassify(FinancialTransaction $transaction, TransactionClassificationEngine $engine): JsonResponse
    {
        return response()->json($engine->process($transaction, false));
    }

    public function approve(
        Request $request,
        FinancialTransaction $transaction,
        VendorMemoryService $memory,
        ProjectCostPostingService $posting,
    ): JsonResponse {
        $data = $request->validate([
            'category' => ['required', 'string'],
            'construction_project_id' => ['nullable', 'exists:construction_projects,id'],
            'learn_vendor_rule' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $before = $transaction->only(['suggested_category', 'construction_project_id', 'classification_status']);
        $transaction->forceFill([
            'suggested_category' => $data['category'],
            'construction_project_id' => $data['construction_project_id'] ?? null,
            'classification_status' => 'approved',
            'classification_confidence' => 1.0,
            'reviewed_at' => now(),
            'reviewed_by' => optional($request->user())->id,
        ])->save();

        TransactionClassificationFeedback::create([
            'financial_transaction_id' => $transaction->id,
            'predicted_category' => $before['suggested_category'],
            'confirmed_category' => $data['category'],
            'confirmed_project_id' => $data['construction_project_id'] ?? null,
            'reviewer_note' => $data['notes'] ?? null,
            'reviewed_by' => optional($request->user())->id,
        ]);

        if ($data['learn_vendor_rule'] ?? true) {
            $memory->learn($transaction, $data['category'], $data['construction_project_id'] ?? null);
        }

        $posting->post($transaction->refresh());

        return response()->json($transaction->refresh());
    }
}

<!doctype html>
<html>
<head>
    <title>Transaction Review Queue</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; background: #f6f7f9; color: #111827; }
        .review-shell { max-width: 1280px; margin: 0 auto; }
        .review-header { display: flex; justify-content: space-between; align-items: flex-end; gap: 18px; margin-bottom: 18px; }
        .muted { color: #6b7280; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; background: white; border: 1px solid #e5e7eb; }
        th, td { padding: 12px 14px; border-bottom: 1px solid #e5e7eb; text-align: left; vertical-align: top; }
        th { background: #f9fafb; color: #374151; font-size: 12px; text-transform: uppercase; }
        tr:last-child td { border-bottom: 0; }
        code { background: #f3f4f6; border-radius: 6px; padding: 2px 6px; }
    </style>
    @include('shared.synergia-styles')
</head>
<body>
    @include('shared.synergia-nav')

    <main class="review-shell">
        <div class="review-header">
            <div>
                <p class="muted">Finance workflow</p>
                <h1>Transaction Review Queue</h1>
                <p class="muted">Confirm uncertain classifications before they affect project costs.</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Vendor</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Suggested Category</th>
                    <th>Confidence</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ optional($transaction->transaction_date)->format('m/d/Y') }}</td>
                        <td>{{ $transaction->merchant ?: 'Unknown' }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td>${{ number_format((float) $transaction->amount, 2) }}</td>
                        <td><code>{{ $transaction->suggested_category }}</code></td>
                        <td>{{ number_format(((float) $transaction->classification_confidence) * 100, 1) }}%</td>
                        <td>{{ str_replace('_', ' ', $transaction->classification_status) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7">No transactions require review.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $transactions->links() }}
    </main>
</body>
</html>

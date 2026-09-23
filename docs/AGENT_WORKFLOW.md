# SynNexus Transaction Classification Agent

## Processing order

1. Import transactions from FreshBooks, bank CSV, or card feed.
2. Normalize merchant, description, amount, and transaction date.
3. Apply deterministic merchant and keyword rules.
4. Search for a likely construction project using client, address, and timing.
5. Assign a confidence score.
6. Auto-approve only high-confidence results.
7. Route medium-confidence items to the review queue.
8. Leave low-confidence items unclassified.
9. Store reviewer corrections as feedback for future rules and model training.

## Required categories

- Direct materials
- Direct subcontractor
- Direct equipment rental
- Direct delivery
- Direct permit
- Direct dumpster/disposal
- Direct project travel
- Vehicle overhead
- Software overhead
- Insurance overhead
- Marketing overhead
- Administrative overhead
- Professional-services overhead
- Owner draw/transfer
- Personal/nonbusiness
- Uncategorized review

## Safety controls

- Never auto-approve Zelle, cash, transfers, restaurants, or generic merchants without project evidence.
- Never convert a personal/nonbusiness suggestion into a deductible business expense automatically.
- Keep the original imported category and raw payload.
- Every correction must be auditable.
- A user must confirm ambiguous transactions.

## Next AI layer

For transactions that remain ambiguous, send structured fields to an LLM and require strict JSON:

```json
{
  "category": "direct_materials",
  "project_id": 123,
  "confidence": 0.78,
  "reason": "Merchant and memo indicate tile purchased during the active project period."
}
```

The AI result should never override deterministic rules or reviewer-confirmed history without creating a review item.

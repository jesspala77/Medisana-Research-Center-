<?php

namespace App\Services;

use App\Models\ProjectEstimate;

class EstimateCalculatorService
{
    public function recalculate(ProjectEstimate $estimate): ProjectEstimate
    {
        $material = 0;
        $labor = 0;
        foreach ($estimate->lineItems as $item) {
            $baseMaterial = (float) $item->quantity * (float) $item->unit_cost;
            $baseLabor = (float) $item->labor_hours * (float) $item->labor_rate;
            $markup = ($baseMaterial + $baseLabor) * ((float) $item->markup_percent / 100);
            $total = $baseMaterial + $baseLabor + $markup;
            $item->update(['line_total' => $total]);
            $material += $baseMaterial;
            $labor += $baseLabor;
        }
        $subtotal = $material + $labor + (float) $estimate->subcontractor_cost + (float) $estimate->overhead_cost;
        $contingency = $subtotal * ((float) $estimate->contingency_percent / 100);
        $markup = ($subtotal + $contingency) * ((float) $estimate->markup_percent / 100);
        $total = $subtotal + $contingency + $markup + (float) $estimate->tax_amount;
        $grossPercent = $total > 0 ? ($markup / $total) * 100 : 0;
        $estimate->update(['material_cost' => $material, 'labor_cost' => $labor, 'total_amount' => $total, 'gross_profit_amount' => $markup, 'gross_profit_percent' => $grossPercent]);

        return $estimate->fresh(['lineItems']);
    }
}

<?php

return [
    'auto_approve_threshold' => (float) env('TX_CLASSIFIER_AUTO_APPROVE', 0.92),
    'review_threshold' => (float) env('TX_CLASSIFIER_REVIEW', 0.65),
    'lookback_days_for_project_match' => (int) env('TX_CLASSIFIER_PROJECT_LOOKBACK', 60),

    'ai' => [
        'enabled' => (bool) env('TX_CLASSIFIER_AI_ENABLED', false),
        'api_key' => env('OPENAI_API_KEY'),
        'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
        'model' => env('TX_CLASSIFIER_AI_MODEL', 'gpt-5-mini'),
        'trigger_below' => (float) env('TX_CLASSIFIER_AI_TRIGGER_BELOW', 0.80),
    ],

    'categories' => [
        'direct_materials', 'direct_subcontractor', 'direct_equipment_rental',
        'direct_delivery', 'direct_permit', 'direct_dumpster_disposal',
        'direct_project_travel', 'direct_design_engineering', 'direct_cleaning',
        'overhead_vehicle', 'overhead_software', 'overhead_insurance',
        'overhead_marketing', 'overhead_admin', 'overhead_professional_services',
        'overhead_facilities', 'overhead_tools', 'overhead_payroll',
        'owner_draw_or_transfer', 'personal_or_nonbusiness', 'uncategorized_review',
    ],

    'merchant_rules' => [
        'home depot' => ['category' => 'direct_materials', 'confidence' => 0.88],
        "lowe's" => ['category' => 'direct_materials', 'confidence' => 0.88],
        'lowes' => ['category' => 'direct_materials', 'confidence' => 0.88],
        'floor & decor' => ['category' => 'direct_materials', 'confidence' => 0.93],
        'sherwin-williams' => ['category' => 'direct_materials', 'confidence' => 0.93],
        'sunbelt rentals' => ['category' => 'direct_equipment_rental', 'confidence' => 0.95],
        'united rentals' => ['category' => 'direct_equipment_rental', 'confidence' => 0.95],
        'waste management' => ['category' => 'direct_dumpster_disposal', 'confidence' => 0.90],
        'miami-dade county' => ['category' => 'direct_permit', 'confidence' => 0.72],
        'adobe' => ['category' => 'overhead_software', 'confidence' => 0.98],
        'microsoft' => ['category' => 'overhead_software', 'confidence' => 0.90],
        'quickbooks' => ['category' => 'overhead_software', 'confidence' => 0.98],
        'freshbooks' => ['category' => 'overhead_software', 'confidence' => 0.98],
        'meta ads' => ['category' => 'overhead_marketing', 'confidence' => 0.98],
        'google ads' => ['category' => 'overhead_marketing', 'confidence' => 0.98],
        'geico' => ['category' => 'overhead_insurance', 'confidence' => 0.82],
        'progressive' => ['category' => 'overhead_insurance', 'confidence' => 0.82],
    ],

    'description_rules' => [
        'permit' => ['category' => 'direct_permit', 'confidence' => 0.80],
        'dumpster' => ['category' => 'direct_dumpster_disposal', 'confidence' => 0.92],
        'delivery' => ['category' => 'direct_delivery', 'confidence' => 0.76],
        'cabinet' => ['category' => 'direct_materials', 'confidence' => 0.82],
        'tile' => ['category' => 'direct_materials', 'confidence' => 0.82],
        'paint' => ['category' => 'direct_materials', 'confidence' => 0.78],
        'labor' => ['category' => 'direct_subcontractor', 'confidence' => 0.72],
        'subcontractor' => ['category' => 'direct_subcontractor', 'confidence' => 0.92],
        'architect' => ['category' => 'direct_design_engineering', 'confidence' => 0.78],
        'engineer' => ['category' => 'direct_design_engineering', 'confidence' => 0.78],
        'cleaning' => ['category' => 'direct_cleaning', 'confidence' => 0.75],
        'zelle' => ['category' => 'uncategorized_review', 'confidence' => 0.40],
        'cash app' => ['category' => 'uncategorized_review', 'confidence' => 0.40],
        'transfer' => ['category' => 'owner_draw_or_transfer', 'confidence' => 0.62],
    ],
];

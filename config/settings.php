<?php
return [
    'minimum_wage' => env('MINIMUM_WAGE', 462.00),
    'duplication_threshold' => env('DUPLICATION_THRESHOLD', 65),

    # 0 = direct matching; 1 = soft matching; 2 = extensive matching enabled
    'extensive_matching' => env('EXTENSIVE_MATCHING', 1), # 1 by default

    'project_number_prefix' => env('PROJECT_NUMBER_PREFIX', 'XII-DCFO-'),
    'batch_number_prefix' => env('BATCH_NUMBER_PREFIX', 'DCFO-BN-'),
];

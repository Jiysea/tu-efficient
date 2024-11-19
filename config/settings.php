<?php
return [
    'minimum_wage' => env('MINIMUM_WAGE', 481.00),
    'duplication_threshold' => env('DUPLICATION_THRESHOLD', 50),
    'project_number_prefix' => env('PROJECT_NUMBER_PREFIX', 'XII-DCFO-'),
    'batch_number_prefix' => env('BATCH_NUMBER_PREFIX', 'DCFO-BN-'),
    'senior_age_threshold' => env('SENIOR_AGE_THRESHOLD', 60),
    'maximum_income' => env('MAXIMUM_INCOME', 1500000),
    'default_archive' => env('DEFAULT_ARCHIVE', 1),
];

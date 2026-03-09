<?php

return [
    'mode'             => 'utf-8',
    'format'           => 'A4',
    'default_font'     => 'solaimanlipi',  // ← add this
    'autoScriptToLang' => true,
    'autoLangToFont'   => true,
    'fontDir'          => [public_path('fonts')],
    'fontdata'         => [
        'solaimanlipi' => [
            'R' => 'SolaimanLipi.ttf',
            'B' => 'SolaimanLipi.ttf',
        ],
    ],
    'tempDir'          => storage_path('app/mpdf'),
];
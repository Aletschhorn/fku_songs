<?php
return [
    'frontend' => [
        'FkuSongs/songreporting-update' => [
            'target' => \FKU\FkuSongs\Middleware\SongReportingStatusUpdate::class,
            'after' => [
                'typo3/cms-frontend/prepare-tsfe-rendering'
            ]
        ],
    ],
];
?>
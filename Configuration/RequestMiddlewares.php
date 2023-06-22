<?php
return [
    'frontend' => [
        'FkuSongs/songreporting-status-update' => [
            'target' => \FKU\FkuSongs\Middleware\SongReportingStatusUpdate::class,
            'after' => [
                'typo3/cms-frontend/prepare-tsfe-rendering'
            ]
        ],
        'FkuSongs/songreporting-vers-update' => [
            'target' => \FKU\FkuSongs\Middleware\SongReportingVersUpdate::class,
            'after' => [
                'typo3/cms-frontend/prepare-tsfe-rendering'
            ]
        ],
    ],
];
?>
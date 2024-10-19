<?php

return [
    'resources' => [
        'enabled' => true,
        'label' => 'Zadanie',
        'plural_label' => 'Zadania',
        'navigation_group' => 'Zadnia',
        'navigation_icon' => 'heroicon-o-cpu-chip',
        'navigation_sort' => null,
        'navigation_count_badge' => false,
        'resource' => Croustibat\FilamentJobsMonitor\Resources\QueueMonitorResource::class,
        'cluster' => null,
    ],
    'pruning' => [
        'enabled' => true,
        'retention_days' => 7,
    ],
    'queues' => [
        'default',
        'generator'
    ],
];

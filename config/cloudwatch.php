<?php

return [
    'driver' => 'custom',
    'credentials' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
    ],
    'group_name' => env('CLOUDWATCH_LOG_GROUP_NAME'),
    'name' => env('CLOUDWATCH_LOG_NAME'),
    'region' => env('AWS_DEFAULT_REGION'),
    'retention' => env('CLOUDWATCH_LOG_RETENTION_DAYS', 14),
    'version' => env('CLOUDWATCH_LOG_VERSION', 'latest'),
    'batch_size' => env('CLOUDWATCH_LOG_BATCH_SIZE', 10000),
    'via' => Zipzoft\LogChannels\CloudwatchLogger::class,
];

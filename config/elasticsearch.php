<?php

use Illuminate\Support\Str;

return [
    'driver' => 'custom',
    'level' => 'error',
    'via'    => Zipzoft\LogChannels\ElasticsearchDriver::class,
    'index' => Str::snake(config('app.name').'_'.config('app.env').'_logs'),
];
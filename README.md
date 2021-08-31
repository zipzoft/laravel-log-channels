## Laravel Log Channels

### Installation
```
composer require zipzoft/laravel-log-channels
```


### Elasticsearch Channel
Required
```
"zipzoft/laravel-elasticsearch-client": "^1.0.0"
```
Add channel to config/logging.php
```php
'channels' => [

    // ...

    'elasticsearch' => [
        'driver' => 'custom',
        'via'    => Zipzoft\LogChannels\ElasticsearchDriver::class,
    ],
],
```

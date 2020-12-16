## Laravel Log Channels

### Installation
```
composer require zipzoft/laravel-log-channels
```


### Elasticsearch Channel
Required
```
"elasticsearch/elasticsearch": "^7.3"
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
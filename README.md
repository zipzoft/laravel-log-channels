## Laravel Log Channels

Error log channel เพิ่มเติมสำหรับ Laravel

สำหรับบางกรณีที่คุณต้องการ channel เพิ่มเติมเพื่อเขียน log เช่น

Elasticsearch, AWS CloudWatch, ...

## ติดตั้ง
```
composer require zipzoft/laravel-log-channels
```




## Channel ต่างๆ

### Elasticsearch

คุณต้องติดตั้ง elasticsearch client ก่อน

โดยรันคำสั่ง

```
php artisan zipzoft:logger-channel:install elasticsearch
```
หรือติดตั้งเองโดยใช้คำสั่ง
```
composer require elasticsearch/elasticsearch
```

หากต้องการแก้ไข config คุณสามารถเพิ่ม Config ที่ config/logging.php
```php
'channels' => [

    // ...

    'elasticsearch' => [
        'driver' => 'custom',
        'via'    => Zipzoft\LogChannels\ElasticsearchDriver::class,
        'index' => 'laravel_app_log',
    ],
],
```



---

### AWS CloudWatch

Channel นี้ต้องการ dependencies
```json
{
  "required" : {
    "aws/aws-sdk-php": "~3.0",
    "maxbanton/cwh": "^2.0"
  }
}
```

ติดตั้งโดยใช้คำสั่ง
```
php artisan zipzoft:logger-channel:install cloudwatch
```

หากต้องการแก้ไข config คุณสามารถเพิ่ม Config ที่ config/logging.php
```php
'channels' => [
    // ...

    'cloudwatch' => [
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
],
```

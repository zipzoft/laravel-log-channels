<?php

namespace Zipzoft\LogChannels;

use Illuminate\Support\Carbon;
use InvalidArgumentException;
use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;
use Aws\CloudWatchLogs\CloudWatchLogsClient;
use Maxbanton\Cwh\Handler\CloudWatch as CloudWatchHandler;
use Monolog\Logger;

class CloudwatchLogger
{

    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    private $app;

    /**
     * @param array $config
     * @return Logger
     * @throws \Exception
     */
    public function __invoke(array $config)
    {
        if ($this->app === null) {
            $this->app = app();
        }

        if (! class_exists("Maxbanton\Cwh\Handler\CloudWatch")) {
            throw new InvalidArgumentException("CloudwatchLogger need run \"php artisan zipzoft:logger-channel:install cloudwatch\"");
        }

        $logger = new Logger($config['name']);
        $logger->pushHandler($this->resolveHandler($config));

        return $logger;
    }

    /**
     * @param array $config
     * @return CloudWatchHandler
     * @throws \Exception
     */
    protected function resolveHandler(array $config)
    {
        $client = new CloudWatchLogsClient($this->getCredentials());

        $retentionDays = $config['retention'];
        $groupName = $config['group_name'];
        $batchSize = $config['batch_size'] ?? 10000;

        $handler = new CloudWatchHandler(
            $client, $groupName, $this->resolveStreamName($config), $retentionDays, $batchSize
        );
        $handler->setFormatter($this->resolveFormatter($config));

        return $handler;
    }

    /**
     * @param array $configs
     * @return mixed
     */
    protected function resolveFormatter(array $configs)
    {
        return new JsonFormatter();
    }

    /**
     * @return array
     */
    protected function getCredentials()
    {
        $loggingConfig = $this->app->make('config')->get('logging.channels');

        if (! isset($loggingConfig['cloudwatch'])) {
            throw new InvalidArgumentException('Configuration Missing for Cloudwatch Log');
        }

        $cloudWatchConfigs = $loggingConfig['cloudwatch'];

        if (! isset($cloudWatchConfigs['region'])) {
            throw new InvalidArgumentException('Missing region key-value');
        }

        $awsCredentials = [
            'region' => $cloudWatchConfigs['region'],
            'version' => $cloudWatchConfigs['version'],
        ];

        if ($cloudWatchConfigs['credentials']['key']) {
            $awsCredentials['credentials'] = $cloudWatchConfigs['credentials'];
        }

        return $awsCredentials;
    }

    /**
     * @param array $config
     * @return string
     */
    protected function resolveStreamName(array $config)
    {
        return $config['name'] . '.' . $this->app->environment().'.'. Carbon::now()->format('Y-m-d_H-i');
    }
}

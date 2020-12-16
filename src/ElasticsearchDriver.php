<?php namespace Zipzoft\LogChannels;

use Elasticsearch\Client as ElasticsearchClient;
use Illuminate\Support\Str;
use Monolog\Formatter\ElasticsearchFormatter;
use Monolog\Handler\ElasticsearchHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class ElasticsearchDriver
{

    /**
     * @param array $config
     * @return LoggerInterface
     */
    public function __invoke(array $config): LoggerInterface
    {
        $formatter = new ElasticsearchFormatter($this->getIndexName($config), 'default');

        $handler = new ElasticsearchHandler($this->getElasticClient());
        $handler->setFormatter($formatter);

        return new Logger('elasticsearch', [$handler]);
    }

    /**
     * @param $config
     * @return string
     */
    private function getIndexName($config)
    {
        if ($config['index'] ?? false) {
            return $config['index'];
        }

        return Str::snake(config('app.name').'_'.config('app.env').'_logs');
    }

    /**
     * @return \Elasticsearch\Client
     */
    protected function getElasticClient()
    {
        return app(ElasticsearchClient::class);
    }
}

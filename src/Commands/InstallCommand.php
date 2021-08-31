<?php

namespace Zipzoft\LogChannels\Commands;


use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Zipzoft\LogChannels\LogChannelServiceProvider;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zipzoft:logger-channel:install {logger : Logger name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install dependencies for logger channel';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->getLoggerName();

        $this->comment("Installing {$name} logger...");

        if ($this->callInstaller($name) !== false) {
            $this->info("Logger {$name} installed successfully.");
        }

        return 0;
    }


    private function getLoggerName()
    {
        $name = strtolower($this->argument('logger'));

        if (in_array($name, LogChannelServiceProvider::LOGGING_DRIVERS)) {
            return $name;
        }

        throw new \InvalidArgumentException("Only supported loggers: ".implode(', ', LogChannelServiceProvider::LOGGING_DRIVERS));
    }

    /**
     * @param string $driver
     * @return mixed|void
     */
    private function callInstaller(string $driver)
    {
        $method = "{$driver}Installer";

        if (method_exists($this, $method)) {
            $installer = $this->$method();
            $installer();
        }
    }

    /**
     * @return \Closure
     */
    private function elasticsearchInstaller()
    {
        return function () {
            $this->requireComposerPackages("elasticsearch/elasticsearch");
        };
    }

    private function cloudwatchInstaller()
    {
        return function () {
            $this->requireComposerPackages("maxbanton/cwh");
        };
    }

    /**
     * Installs the given Composer Packages into the application.
     *
     * @param  mixed  $packages
     * @return void
     */
    private function requireComposerPackages($packages)
    {
        $command = array_merge(
            ['composer', 'require'],
            is_array($packages) ? $packages : func_get_args()
        );

        (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }
}

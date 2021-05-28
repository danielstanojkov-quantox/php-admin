<?php

namespace App\Helpers;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Log
{
    /**
     * Logger instance
     *
     * @var Logger
     */
    public $logger;

    /**
     *
     * @var File $file
     */
    private $file;

    /**
     * Log constructor
     */
    public function __construct(File $file)
    {
        $this->file = $file;
        $this->createLogFile();

        $this->logger = new Logger('phpAdmin');
        $this->logger->pushHandler(new StreamHandler(app('logs'), Logger::DEBUG));
    }

    /**
     * Create Log File if neccessary
     *
     * @return void
     */
    public function createLogFile(): void
    {
        if ($this->file->exists(app('logs'))) {
            return;
        }

        $this->file->makeDirectory('storage/logs');
        $this->file->makeFile('storage/logs/phpAdmin.log', '');
    }

    /**
     * Logs error messages
     *
     * @param string $message
     * @return void
     */
    public function error(string $message): void
    {
        $this->logger->error($message);
    }

    /**
     * Logs warning messages
     *
     * @param string $message
     * @return void
     */
    public function warning(string $message): void
    {
        $this->logger->warning($message);
    }

    /**
     * Logs info messages
     *
     * @param string $message
     * @return void
     */
    public function info(string $message): void
    {
        $this->logger->info($message);
    }
}

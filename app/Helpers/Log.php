<?php

namespace App\Helpers;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Log
{
    /**
     * Logger instance
     *
     * @var mixed
     */
    public static $logger = null;

    /**
     * Log constructor
     */
    public function __construct()
    {
        $this->createLogFile();

        static::$logger = new Logger('phpAdmin');
        static::$logger->pushHandler(new StreamHandler(app('logs'), Logger::WARNING));
    }

    /**
     * Create Log File if neccessary
     *
     * @return void
     */
    public function createLogFile(): void
    {
        if (File::exists(app('logs'))) {
            return;
        }

        File::makeDirectory('storage/logs');
        File::makeFile('storage/logs/phpAdmin.log', '');
    }

    /**
     * Get instance method
     *
     * @return object
     */
    public static function getInstance(): object
    {
        if (static::$logger == null) {
            new Log;
        }

        return static::$logger;
    }

    /**
     * Logs error messages
     *
     * @param string $message
     * @return void
     */
    public static function error(string $message): void
    {
        $logger = static::getInstance();
        $logger->error($message);
    }

    /**
     * Logs warning messages
     *
     * @param string $message
     * @return void
     */
    public static function warning(string $message): void
    {
        $logger = static::getInstance();
        $logger->warning($message);
    }
}

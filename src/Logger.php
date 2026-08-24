<?php

declare(strict_types=1);

namespace App;

use Monolog\Level;
use Monolog\Logger as Monolog;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

/**
 * Single logging entry point for the app. Feature code calls this class, never
 * echo/error_log/var_dump or a raw Monolog instance — one place to change sink
 * and level.
 */
final class Logger
{
    private LoggerInterface $inner;

    public function __construct(string $logFile, string $level = 'info')
    {
        $monolog = new Monolog('web-help-system');
        $monolog->pushHandler(new StreamHandler($logFile, Level::fromName(ucfirst($level))));
        $this->inner = $monolog;
    }

    public function info(string $message, array $context = []): void
    {
        $this->inner->info($message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->inner->warning($message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->inner->error($message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->inner->debug($message, $context);
    }
}

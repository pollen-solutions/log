<?php

declare(strict_types=1);

namespace Pollen\Log;

use Pollen\Support\Proxy\ContainerProxyInterface;
use Psr\Log\LoggerInterface as BaseLoggerInterface;
use Pollen\Support\Concerns\ParamsBagAwareTraitInterface;

/**
 * @mixin \Monolog\Logger
 */
interface LoggerInterface extends
    BaseLoggerInterface,
    ContainerProxyInterface,
    ParamsBagAwareTraitInterface
{
    /**
     * Sets related log manager instance.
     *
     * @param LogManagerInterface $logManager
     *
     * @return static
     */
    public function setLogManager(LogManagerInterface $logManager): LoggerInterface;

    /**
     * Adds a log record at the Success level.
     *
     * @param string $message
     * @param array $context
     *
     * @return void
     */
    public function success(string $message, array $context = []): void;
}
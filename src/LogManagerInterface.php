<?php

declare(strict_types=1);

namespace Pollen\Log;

use Pollen\Support\Concerns\ConfigBagAwareTraitInterface;
use Pollen\Support\Proxy\ContainerProxyInterface;

/**
 * @mixin Logger
 */
interface LogManagerInterface extends ConfigBagAwareTraitInterface, ContainerProxyInterface
{
    /**
     * Adds a logger instance to the registered list.
     *
     * @param LoggerInterface $channel
     *
     * @return static
     */
    public function addChannel(LoggerInterface $channel): LogManagerInterface;

    /**
     * Gets a logger instance from the registered list.
     *
     * @param string|null $name
     *
     * @return LoggerInterface
     */
    public function channel(?string $name = null): LoggerInterface;

    /**
     * Gets the default logger instance.
     *
     * @return LoggerInterface
     */
    public function getDefault(): LoggerInterface;

    /**
     * Gets the default log files absolute storage path.
     *
     * @return string
     */
    public function getDefaultStoragePath(): ?string;

    /**
     * Registers a named logger from its parameters.
     *
     * @param string $name
     * @param array $params
     *
     * @return LoggerInterface|null
     */
    public function registerChannel(string $name, array $params = []): ?LoggerInterface;

    /**
     * Sets the default logger instance.
     *
     * @param LoggerInterface $default
     *
     * @return static
     */
    public function setDefault(LoggerInterface $default): LogManagerInterface;

    /**
     * Sets the default log files absolute storage path.
     *
     * @param string $storagePath
     *
     * @return static
     */
    public function setDefaultStoragePath(string $storagePath): LogManagerInterface;
}
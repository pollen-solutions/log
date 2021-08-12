<?php

declare(strict_types=1);

namespace Pollen\Log;

use Monolog\Logger as BaseLogger;
use Pollen\Support\Concerns\ParamsBagAwareTrait;
use Pollen\Support\Proxy\ContainerProxy;
use Psr\Log\InvalidArgumentException;

class Logger extends BaseLogger implements LoggerInterface
{
    use ContainerProxy;
    use LoggerDefaultHandlersTrait;
    use ParamsBagAwareTrait;

    /**
     * @var int
     */
    public const SUCCESS = 225;

    /**
     * This is a static variable and not a constant to serve as an extension point for custom levels.
     * @var array<int, string> $levels Logging levels with the levels as key
     */
    protected static $levels = [
        self::DEBUG     => 'DEBUG',
        self::INFO      => 'INFO',
        self::SUCCESS   => 'SUCCESS',
        self::NOTICE    => 'NOTICE',
        self::WARNING   => 'WARNING',
        self::ERROR     => 'ERROR',
        self::CRITICAL  => 'CRITICAL',
        self::ALERT     => 'ALERT',
        self::EMERGENCY => 'EMERGENCY',
    ];

    /**
     * Related log manager instance.
     * @var LogManagerInterface|null
     */
    protected ?LogManagerInterface $logManager = null;

    /**
     * @inheritDoc
     */
    public function addRecord($level, $message, array $context = []): bool
    {
        if (!$this->getHandlers()) {
            $this->registerDefaultHandlers();
        }

        return parent::addRecord($level, $message, $context);
    }

    /**
     * @inheritDoc
     */
    public function setLogManager(LogManagerInterface $logManager): LoggerInterface
    {
        $this->logManager = $logManager;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function success(string $message, array $context = []): void
    {
        $this->addRecord(self::SUCCESS, $message, $context);
    }

    /**
     * @inheritDoc
     * {@internal Add Success as Allowed level.}
     */
    public static function toMonologLevel($level): int
    {
        if (is_string($level)) {
            if (is_numeric($level)) {
                return (int)$level;
            }

            $upper = strtr($level, 'abcdefgilmnorstuwy', 'ABCDEFGILMNORSTUWY');
            if (defined(__CLASS__ . '::' . $upper)) {
                return constant(__CLASS__ . '::' . $upper);
            }

            throw new InvalidArgumentException(
                'Level "' . $level . '" is not defined, use one of: ' . implode(', ', array_keys(static::$levels))
            );
        }

        if (!is_int($level)) {
            throw new InvalidArgumentException(
                'Level "' . var_export($level, true) . '" is not defined, use one of: ' . implode(
                    ', ',
                    array_keys(static::$levels)
                )
            );
        }

        return $level;
    }
}
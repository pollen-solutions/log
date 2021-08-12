<?php

declare(strict_types=1);

namespace Pollen\Log;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Pollen\Support\DateTime;
use Pollen\Support\Filesystem as fs;
use RuntimeException;
use Throwable;

trait LoggerDefaultHandlersTrait
{
    /**
     * @var HandlerInterface[]|array
     */
    protected array $defaultHandlers = [];

    /**
     * @var callable|null
     */
    protected $defaultHandlerFallback;

    /**
     * Gets the default handler if none is registered.
     *
     * @return HandlerInterface
     */
    protected function getDefaultHandlerFallback(): HandlerInterface
    {
        $fb = $this->defaultHandlerFallback;

        if (is_callable($fb)) {
            return $fb($this);
        }

        if ($this->logManager instanceof LogManagerInterface) {
            $path = $this->logManager->getDefaultStoragePath();
        }

        $defaultFile = $this->getName() . '.log';
        if(!$filename = $this->params('filename')) {
            $filename = isset($path) ? fs::normalizePath($path . fs::DS . $defaultFile) : $defaultFile;
        }

        $defaultHandler = (new RotatingFileHandler(
            $filename,
            $this->params('rotate') ?: 10,
            $this->params('level') ?: self::DEBUG
        ))->setFormatter(
            new LineFormatter(
                $this->params('format'), $this->params('date_format')
            )
        );

        static::setTimezone($this->params('timezone', (new DateTime())->getTimezone()));

        return $defaultHandler;
    }

    /**
     * Registers the default handler instances.
     *
     * @return void
     */
    public function registerDefaultHandlers(): void
    {
        if (empty($this->defaultHandlers)) {
            try {
                $this->defaultHandlers[] = $this->getDefaultHandlerFallback();
            } catch (Throwable $e) {
                throw new RuntimeException(
                    sprintf(
                        'The logger default handler fallback could be returns %s instance.', HandlerInterface::class
                    )
                );
            }
        }

        foreach ($this->defaultHandlers as $defaultHandler) {
            $this->pushHandler($defaultHandler);
        }
    }

    /**
     * Sets the default handler instances.
     *
     * @param HandlerInterface[] $defaultHandlers
     *
     * @return void
     */
    public function setDefaultsHandlers(array $defaultHandlers): void
    {
        foreach ($defaultHandlers as $defaultHandler) {
            if (!$defaultHandler instanceof HandlerInterface) {
                throw new RuntimeException('DefaultHandler must be an instance of HandlerInterface');
            }
        }

        $this->defaultHandlers = $defaultHandlers;
    }
}
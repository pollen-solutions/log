<?php

declare(strict_types=1);

namespace Pollen\Log;

use Pollen\Container\BootableServiceProvider;
use Pollen\Event\EventDispatcherInterface;
use Pollen\Kernel\Events\ConfigLoadEvent;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class LogServiceProvider extends BootableServiceProvider
{
    protected $provides = [
        LogManagerInterface::class
    ];

    /**
     * @inheritDoc
     */
    public function boot(): void
    {
        try {
            /** @var EventDispatcherInterface $event */
            if ($event = $this->getContainer()->get(EventDispatcherInterface::class)) {
                $event->subscribeTo('config.load', static function (ConfigLoadEvent $event) {
                    if (is_callable($config = $event->getConfig('log'))) {
                        $config($event->getApp()->get(LogManagerInterface::class), $event->getApp());
                    }
                });
            }
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            unset($e);
        }
    }

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share(LogManagerInterface::class, function () {
            return new LogManager($this->getContainer());
        });
    }
}
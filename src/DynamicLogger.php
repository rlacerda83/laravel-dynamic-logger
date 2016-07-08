<?php

namespace DynamicLogger;

use Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Log;

class DynamicLogger
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Command
     */
    protected $cliLogger;

    /**
     * @var bool
     */
    protected $fileLoggerActive;

    /**
     * @var array
     */
    protected $handlers;

    /**
     * DynamicLogger constructor.
     */
    public function __construct()
    {
        $this->logger = Log::getMonolog();
        $this->handlers = $this->logger->getHandlers();
    }

    /**
     * @param array $handlers
     * @param bool $logOnlyThisHandlers
     * @param bool $cliLogger
     */
    public function changeLog(
        array $handlers,
        $logOnlyThisHandlers = false,
        $cliLogger = false
    ) {
        $this->setHandlers($handlers, $logOnlyThisHandlers);
        $this->cliLogger = $cliLogger;
    }

    /**
     *
     */
    public function revert()
    {
        $this->logger->setHandlers($this->handlers);
    }

    /**
     * @param $handlers
     * @param $logOnlyThisHandler
     * @return bool
     */
    protected function setHandlers(
        $handlers,
        $logOnlyThisHandler
    ) {
        if ($logOnlyThisHandler) {
            $this->logger->setHandlers($handlers);
            return true;
        }

        foreach ($handlers as $handler) {
            $this->logger->pushHandler($handler);
        }
    }

    /**
     * @param $message
     * @param array $params
     * @param array $context
     * @return bool
     */
    public function addInfo($message, $params = [], $context = [])
    {
        if ($this->fileLoggerActive) {
            $this->addInfoFile($message, $params, $context);
        }

        if ($this->cliLogger) {
            $this->addInfoCli($message, $params);
        }
    }

    /**
     * @param $message
     * @param array $params
     * @param array $context
     * @return bool
     */
    protected function addInfoFile($message, $params = [], $context = [])
    {
        if (count($params)) {
            $this->logger->addInfo(
                vsprintf(
                    $message,
                    $params
                ),
                $context
            );

            return true;
        }

        $this->logger->addInfo($message, $context);
    }

    /**
     * @param $message
     * @param $params
     */
    protected function addInfoCli($message, $params)
    {
        $this->cliLogger->line('');
        $this->cliLogger->info(
            vsprintf(
                $message,
                $params
            )
        );
    }
}
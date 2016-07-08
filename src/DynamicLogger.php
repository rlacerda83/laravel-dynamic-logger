<?php

namespace DynamicLogger;

use Monolog\Handler\NullHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
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
     * @param $path
     * @param bool $logOnlyThisHandler
     * @param bool $fileLoggerActive
     * @param null $cliLogger
     */
    public function changeLog(
        $path,
        $logOnlyThisHandler = false,
        $fileLoggerActive = true,
        $cliLogger = null
    ) {
        $logger = Log::getMonolog();
        $this->setHandlers($logger, $path, $logOnlyThisHandler, $fileLoggerActive);

        $this->logger = $logger;
        $this->cliLogger = $cliLogger;
        $this->fileLoggerActive = $fileLoggerActive;
    }

    /**
     * @param Logger $logger
     * @param $path
     * @param $logOnlyThisHandler
     * @param bool $fileLoggerActive
     * @return bool
     */
    protected function setHandlers(
        Logger $logger,
        $path,
        $logOnlyThisHandler,
        $fileLoggerActive = true
    ) {
        if (!$fileLoggerActive) {
            $logger->setHandlers([new NullHandler()]);
            return true;
        }

        $streamHandler = new StreamHandler($path);
        if ($logOnlyThisHandler) {
            $logger->setHandlers([$streamHandler]);
            return true;
        }

        $logger->pushHandler($streamHandler);
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
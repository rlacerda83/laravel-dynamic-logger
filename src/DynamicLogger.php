<?php

namespace DynamicLogger;

use Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Log;
use Psr\Log\LoggerInterface;

class DynamicLogger implements LoggerInterface
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
    public function setHandlers(
        array $handlers,
        $logOnlyThisHandlers = false,
        $cliLogger = false
    ) {
        $this->setupHandlers($handlers, $logOnlyThisHandlers);
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
    protected function setupHandlers(
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

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $params
     * @param array $context
     * @return null
     */
    public function emergency($message, array $params = array(), array $context = array())
    {
        $logMessage = $this->createMessage($message, $params);
        $this->logger->emergency($logMessage, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $params
     * @param array $context
     * @return null
     */
    public function alert($message, array $params = array(), array $context = array())
    {
        $logMessage = $this->createMessage($message, $params);
        $this->logger->alert($logMessage, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $params
     * @param array $context
     * @return null
     */
    public function critical($message, array $params = array(), array $context = array())
    {
        $logMessage = $this->createMessage($message, $params);
        $this->logger->critical($logMessage, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $params
     * @param array $context
     * @return null
     */
    public function error($message, array $params = array(), array $context = array())
    {
        $logMessage = $this->createMessage($message, $params);
        $this->logger->error($logMessage, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $params
     * @param array $context
     * @return null
     */
    public function warning($message, array $params = array(), array $context = array())
    {
        $logMessage = $this->createMessage($message, $params);
        $this->logger->warning($logMessage, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $params
     * @param array $context
     * @return null
     */
    public function notice($message, array $params = array(), array $context = array())
    {
        $logMessage = $this->createMessage($message, $params);
        $this->logger->notice($logMessage, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $params
     * @param array $context
     * @return null
     */
    public function info($message, array $params = array(), array $context = array())
    {
        $logMessage = $this->createMessage($message, $params);
        $this->logger->info($logMessage, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $params
     * @param array $context
     * @return null
     */
    public function debug($message, array $params = array(), array $context = array())
    {
        $logMessage = $this->createMessage($message, $params);
        $this->logger->debug($logMessage, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $params
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $params = array(), array $context = array())
    {
        $logMessage = $this->createMessage($message, $params);
        $this->logger->log($level, $logMessage, $context);
    }

    /**
     * Generate message
     *
     * @param $message
     * @param array $params
     * @return string
     */
    protected function createMessage($message, array $params = array())
    {
        if (!count($params)) {
            return $message;
        }

        return vsprintf(
            $message,
            $params
        );
    }
}
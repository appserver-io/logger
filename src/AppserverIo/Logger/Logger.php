<?php

/**
 * AppserverIo\Logger\Logger
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @category  Library
 * @package   Routlt
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://github.com/appserver-io/logger
 * @link      http://www.appserver.io
 */

namespace AppserverIo\Logger;

use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;

/**
 * Thread-Safe and PSR-3 compatible logger implementation.
 *
 * @category  Library
 * @package   Logger
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://github.com/appserver-io/logger
 * @link      http://www.appserver.io
 */
class Logger extends \Worker implements LoggerInterface
{

    /**
     * Initializes the logger instance with the log level.
     *
     * @param string $channelName The channel name
     * @param array  $handlers    The array with the handlers
     * @param array  $processors  The array with the processors
     */
    public function __construct($channelName, array $handlers = array(), array $processors = array())
    {

        // initialize the members
        $this->run = true;

        // initialize the passe values
        $this->channelName = $channelName;

        // initialize the stackables
        $this->stack = new \Stackable();
        $this->handlers = new \Stackable();
        $this->processors = new \Stackable();

        // add the passed handlers
        foreach ($handlers as $handler) {
            $this->addHandler($handler);
        }

        // add the passed processors
        foreach ($processors as $processor) {
            $this->addProcessor($processor);
        }
    }

    /**
     * Adds the passed handler.
     *
     * @param object $handler The handler to be added
     *
     * @return void
     */
    public function addHandler($handler)
    {
        $this->handlers[] = $handler;
    }

    /**
     * Returns the registered handlers.
     *
     * @return \Stackable The registered handlers
     */
    protected function getHandlers()
    {
        return $this->handlers;
    }

    /**
     * Adds the passed processor.
     *
     * @param object $processor The processor to be added
     *
     * @return void
     */
    public function addProcessor($processor)
    {
        $this->processors[] = $processor;
    }

    /**
     * Returns the registered processors.
     *
     * @return \Stackable The registered processors
     */
    protected function getProcessors()
    {
        return $this->processors;
    }

    /**
     * Returns the channel name, passed to the constructor.
     *
     * @return string The channel name
     */
    protected function getChannelName()
    {
        return $this->channelName;
    }

    /**
     * System is unusable.
     *
     * @param string $message The message to log
     * @param array  $context The context for log
     *
     * @return void
     */
    public function emergency($message, array $context = array())
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message The message to log
     * @param array  $context The context for log
     *
     * @return void
     */
    public function alert($message, array $context = array())
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message The message to log
     * @param array  $context The context for log
     *
     * @return void
     */
    public function critical($message, array $context = array())
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message The message to log
     * @param array  $context The context for log
     *
     * @return void
     */
    public function error($message, array $context = array())
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message The message to log
     * @param array  $context The context for log
     *
     * @return void
     */
    public function warning($message, array $context = array())
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message The message to log
     * @param array  $context The context for log
     *
     * @return null
     */
    public function notice($message, array $context = array())
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message The message to log
     * @param array  $context The context for log
     *
     * @return void
     */
    public function info($message, array $context = array())
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message The message to log
     * @param array  $context The context for log
     *
     * @return void
     */
    public function debug($message, array $context = array())
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level   The log level
     * @param string $message The message to log
     * @param array  $context The context for log
     *
     * @return void
     */
    public function log($level, $message, array $context = array())
    {

        // create a new log message
        $logMessage = new LogMessage($id = uniqid(), $level, $message, $context);

        // queue the log message
        $this->stack[$id] = $logMessage;

        // put it on the stack
        $this->stack($logMessage);
    }

    /**
     * Targe for the delegate method of the log message that processes
     * the log message asynchronously.
     *
     * @param \AppserverIo\Logger\LogMessageInterface $logMessage The message to be processed
     *
     * @return void
     */
    public function process(LogMessageInterface $logMessage)
    {

        // let the handler log the message
        foreach ($this->getHandlers() as $handler) {
            $handler->log($logMessage->getLevel(), $logMessage->getMessage(), $logMessage->getContext());
        }

        // remove the message from the stack to free memory
        unset($this->stack[$logMessage->getId()]);
    }
}

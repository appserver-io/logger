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
class Logger extends \Thread implements LoggerInterface
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
        $this->ready = false;

        // initialize the passe values
        $this->channelName = $channelName;
        $this->handlers = $handlers;
        $this->processors = $processors;
    }

    /**
     * Returns the channel name, passed to the constructor.
     *
     * @return string The channel name
     */
    public function getChannelName()
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

        $this->synchronized(function ($self, $lvl, $msg, $ctx) {

            // prepare log data
            $self->self->level = $lvl;
            $self->message = $msg;
            $self->context = $ctx;

            // send the notification that we're ready
            $self->notify();

        }, $this, $level, $message, $context);
    }

    /**
     * Executes the logging functionality.
     *
     * @return void
     */
    public function run()
    {

        while ($this->run) { // run forever and wait for log messages

            $this->synchronized(function ($self) {

                // wait until we receive a new log notification
                $self->wait();

                // run over all handlers and log the message
                foreach ($self->handlers as $handler) {

                    // prepare log data
                    $level = $self->level;
                    $message = $self->message;
                    $context = $self->context;

                    // let the handler log the message
                    $handler->log($level, $message, $context);
                }

            }, $this);
        }
    }
}

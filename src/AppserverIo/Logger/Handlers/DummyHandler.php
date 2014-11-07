<?php

/**
 * AppserverIo\Logger\Handlers\DummyHandler
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @category   Library
 * @package    Logger
 * @subpackage Handlers
 * @author     Tim Wagner <tw@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://github.com/appserver-io/logger
 * @link       http://www.appserver.io
 */

namespace AppserverIo\Logger\Handlers;

use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;
use AppserverIo\Logger\LoggerUtils;

/**
 * A dummy logger implementation.
 *
 * @category   Library
 * @package    Logger
 * @subpackage Handlers
 * @author     Tim Wagner <tw@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://github.com/appserver-io/logger
 * @link       http://www.appserver.io
 */
class DummyHandler implements LoggerInterface
{

    /**
     * The log level we want to use.
     *
     * @var string
     */
    protected $logLevel;

    /**
     * The available log levels.
     *
     * @var array
     */
    protected $logLevels;

    /**
     * Initializes the handler instance with channel name and log level.
     *
     * @param integer $logLevel The log level we want to use
     */
    public function __construct($logLevel = LogLevel::INFO)
    {

        // initialize the available log levels
        $this->logLevels = LoggerUtils::$logLevels;

        // set the actual log level
        $this->logLevel = $logLevel;
    }

    /**
     * System is unusable.
     *
     * @param string $message The message to log
     * @param array  $context The context for log
     *
     * @return null
     */
    public function emergency($message, array $context = array())
    {
        $this->log($message, $context);
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
     * @return null
     */
    public function alert($message, array $context = array())
    {
        $this->log($message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message The message to log
     * @param array  $context The context for log
     *
     * @return null
     */
    public function critical($message, array $context = array())
    {
        $this->log($message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message The message to log
     * @param array  $context The context for log
     *
     * @return null
     */
    public function error($message, array $context = array())
    {
        $this->log($message, $context);
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
     * @return null
     */
    public function warning($message, array $context = array())
    {
        $this->log($message, $context);
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
        $this->log($message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message The message to log
     * @param array  $context The context for log
     *
     * @return null
     */
    public function info($message, array $context = array())
    {
        $this->log($message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message The message to log
     * @param array  $context The context for log
     *
     * @return null
     */
    public function debug($message, array $context = array())
    {
        $this->log($message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level   The log level
     * @param string $message The message to log
     * @param array  $context The context for log
     *
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        // this is a dummy logger
    }

    /**
     * Returns the log level we want to use.
     *
     * @return integer The log level
     */
    protected function getLogLevel()
    {
        return $this->logLevel;
    }

    /**
     * Returns TRUE if the handler should log a message based on the actual
     * log level, else FALSE.
     *
     * @param string $level The log level to query
     *
     * @return boolean TRUE if the handler should log
     */
    public function shouldLog($level)
    {
        return $this->logLevels[$level] >= $this->logLevels[$this->getLogLevel()];
    }
}

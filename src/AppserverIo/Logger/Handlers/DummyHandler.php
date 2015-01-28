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
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2015 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://github.com/appserver-io/logger
 * @link      http://www.appserver.io
 */

namespace AppserverIo\Logger\Handlers;

use Psr\Log\LogLevel;
use AppserverIo\Logger\LoggerUtils;
use AppserverIo\Logger\LogMessageInterface;
use AppserverIo\Logger\Formatters\FormatterInterface;
use AppserverIo\Logger\Formatters\StandardFormatter;

/**
 * A dummy logger implementation.
 *
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2015 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://github.com/appserver-io/logger
 * @link      http://www.appserver.io
 */
class DummyHandler implements HandlerInterface
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
     * The formatter instance.
     *
     * @var \AppserverIo\Logger\Formatters\FormatterInterface
     */
    protected $formatter;

    /**
     * Initializes the handler instance with channel name and log level.
     *
     * @param integer $logLevel The log level we want to use
     */
    public function __construct($logLevel = LogLevel::INFO)
    {

        // initialize the available log levels and formatter
        $this->logLevels = LoggerUtils::$logLevels;
        $this->formatter = new StandardFormatter();

        // set the actual log level
        $this->logLevel = $logLevel;
    }

    /**
     * Sets the formatter for this handler.
     *
     * @param \AppserverIo\Logger\Formatters\FormatterInterface $formatter The formatter instance
     *
     * @return void
     */
    public function setFormatter(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * Returns the formatter for this handler.
     *
     * @return \AppserverIo\Logger\Formatters\FormatterInterface The formatter instance
     */
    public function getFormatter()
    {
        return $this->formatter;
    }

    /**
     * Handles the log message.
     *
     * @param \AppserverIo\Logger\LogMessageInterface $logMessage The message to be handled
     *
     * @return void
     */
    public function handle(LogMessageInterface $logMessage)
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
    protected function shouldLog($level)
    {
        return $this->logLevels[$level] >= $this->logLevels[$this->getLogLevel()];
    }
}

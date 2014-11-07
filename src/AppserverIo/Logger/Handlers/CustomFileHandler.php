<?php

/**
 * AppserverIo\Logger\Handlers\CustomFileHandler
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

/**
 * Logger implementation that uses the PHP 'error_log' function to log to a custom file.
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
class CustomFileHandler extends DummyHandler
{

    /**
     * The file we want to log to.
     *
     * @var string
     */
    protected $logFile;

    /**
     * Initializes the logger instance with the log level.
     *
     * @param string  $channelName The channel name
     * @param integer $logLevel    The log level we want to use
     * @param string  $logFile     The file we want to log to
     */
    public function __construct($channelName, $logLevel = LogLevel::INFO, $logFile = null)
    {

        // pass arguments to parent constructor
        parent::__construct($channelName, $logLevel);

        // set the file we want to log to
        $this->logFile = $logFile;
    }

    /**
     * Returns the relative path to the file we want to log to.
     *
     * @return string The file we want to log to
     */
    protected function getLogFile()
    {
        return $this->logFile;
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

        // check the log level
        if ($this->shouldLog($level)) {

            // prepare the log message
            $logMessage = sprintf('[%s] - %s (%s): %s %s' . PHP_EOL, date('Y-m-d H:i:s'), gethostname(), $level, $message, json_encode($context));

            // write the log message
            error_log($logMessage, 3, $this->getLogFile());
        }
    }
}

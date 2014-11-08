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
use AppserverIo\Logger\LogMessageInterface;

/**
 * Logger implementation that uses the PHP error_log() function to log to a custom file.
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
     * @param string  $logFile  The file we want to log to
     * @param integer $logLevel The log level we want to use
     */
    public function __construct($logFile, $logLevel = LogLevel::INFO)
    {

        // pass arguments to parent constructor
        parent::__construct($logLevel);

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
     * Handles the log message.
     *
     * @param \AppserverIo\Logger\LogMessageInterface $logMessage The message to be handled
     *
     * @return void
     */
    public function handle(LogMessageInterface $logMessage)
    {
        if ($this->shouldLog($logMessage->getLevel())) { // check the log level
            error_log($this->getFormatter()->format($logMessage) . PHP_EOL, 3, $this->getLogFile());
        }
    }
}

<?php

/**
 * AppserverIo\Logger\Handlers\ErrorLogHandler
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
 * Logger implementation that uses the PHP 'error_log' function.
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
class ErrorLogHandler extends DummyHandler
{

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
            $logMessage = sprintf('[%s] - %s (%s): %s %s', date('Y-m-d H:i:s'), gethostname(), $level, $message, json_encode($context));

            // write the log message
            error_log($logMessage);
        }
    }
}

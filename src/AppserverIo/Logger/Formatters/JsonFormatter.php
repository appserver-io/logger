<?php

/**
 * AppserverIo\Logger\Handlers\JsonFormatter
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

namespace AppserverIo\Logger\Formatters;

use AppserverIo\Logger\LogMessageInterface;

/**
 * A formatter that encodes the formatted message with PHP json_encode() method.
 *
 * @category   Library
 * @package    Logger
 * @subpackage Handlers
 * @author     Tim Wagner <tw@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://github.com/appserver-io/logger
 * @link       http://www.appserver.io
 * @link       http://php.net/manual/en/function.sprintf.php
 */
class JsonFormatter extends StandardFormatter
{

    /**
     * Initializes the handler instance with channel name and log level.
     *
     * @param string|null $messageFormat The message format, MUST be valid for sprintf
     * @param string|null $dateFormat    The date format, valid for PHP date() function
     */
    public function __construct($messageFormat = null, $dateFormat = null)
    {

        // initialize message and date format
        if ($messageFormat == null) {
            $messageFormat = '{ "timestamp": "%s", "id": "%s", "level" : "%s", "message": "%s", "context": %s }';
        }

        // pass variables to parent
        parent::__construct($messageFormat, $dateFormat);
    }

    /**
     * Formats and returns a json encoded string representation of the passed
     * log message.
     *
     * @param \AppserverIo\Logger\Formatters\LogMessageInterface $logMessage The log message we want to format
     *
     * @return string The json encoded and formatted string representation of the log messsage
     * @see \AppserverIo\Logger\Formatters\JsonFormatter::format()
     */
    public function format(LogMessageInterface $logMessage)
    {
        return json_encode(parent::format($logMessage));
    }
}

<?php

/**
 * AppserverIo\Logger\Handlers\StandardFormatter
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

namespace AppserverIo\Logger\Formatters;

use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;
use AppserverIo\Logger\LogMessageInterface;

/**
 * The default formatter that uses vsprintf() to format the message.
 *
 * The following arguments are available and passed to vsprintf()
 * method in the given order:
 *
 * 1. date (formatted with the date format passed to the constructor)
 * 2. hostname (queried by PHP gethostname() method)
 * 3. loglevel
 * 4. message
 * 5. context (always JSON encoded)
 *
 * If you want to change the order of the arguments have a look at
 * the sprintf() documentation.
 *
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2015 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://github.com/appserver-io/logger
 * @link      http://www.appserver.io
 * @see       http://php.net/manual/en/function.sprintf.php
 */
class StandardFormatter implements FormatterInterface
{

    /**
     * The message format to use, timestamp, id, line, message, context
     *
     * @var string
     */
    protected $messageFormat = '[%s] - %s (%s): %s %s';

    /**
     * The date format to use.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Initializes the handler instance with channel name and log level.
     *
     * @param string|null $messageFormat The message format, MUST be valid for sprintf
     * @param string|null $dateFormat    The date format, valid for PHP date() function
     */
    public function __construct($messageFormat = null, $dateFormat = null)
    {
        // initialize message and date format
        if ($messageFormat != null && is_string($messageFormat)) {
            $this->messageFormat = $messageFormat;
        }
        if ($dateFormat != null && is_string($dateFormat)) {
            $this->dateFormat = $dateFormat;
        }
    }

    /**
     * Formats and returns a string representation of the passed log message.
     *
     * @param \AppserverIo\Logger\LogMessageInterface $logMessage The log message we want to format
     *
     * @return string The formatted string representation for the log messsage
     */
    public function format(LogMessageInterface $logMessage)
    {

        // initialize the parameters for the formatted message
        $params = array(
            date($this->dateFormat),
            gethostname(),
            $logMessage->getLevel(),
            $logMessage->getMessage(),
            json_encode($logMessage->getContext())
        );

        // format, trim and return the message
        return trim(vsprintf($this->messageFormat, $params));
    }
}

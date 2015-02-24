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
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2015 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://github.com/appserver-io/logger
 * @link      http://www.appserver.io
 */

namespace AppserverIo\Logger\Formatters;

/**
 * A formatter that encodes the formatted message with PHP json_encode() method.
 *
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2015 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://github.com/appserver-io/logger
 * @link      http://www.appserver.io
 * @see       http://php.net/manual/en/function.sprintf.php
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
}

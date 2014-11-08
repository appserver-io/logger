<?php

/**
 * AppserverIo\Logger\LogMessageInterface
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
 * @package   Logger
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://github.com/appserver-io/logger
 * @link      http://www.appserver.io
 */

namespace AppserverIo\Logger;

/**
 * Interface for thread-safe log messages.
 *
 * @category  Library
 * @package   Logger
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://github.com/appserver-io/logger
 * @link      http://www.appserver.io
 */
interface LogMessageInterface
{

    /**
     * Returns the unique-ID of the log message.
     *
     *  @return string The unique-ID
     */
    public function getId();

    /**
     * Returns the log level.
     *
     * @return string The log level
     * @link https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#5-psrlogloglevel
     */
    public function getLevel();

    /**
     * Returns the message we want to log.
     *
     * @return string The message to be logged
     * @link https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#12-message
     */
    public function getMessage();

    /**
     * Returns the context we're logging.
     *
     * @return array The log context
     * @link https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#13-context
     */
    public function getContext();
}

<?php

/**
 * AppserverIo\Logger\LoggerUtils
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

use Psr\Log\LogLevel;

/**
 * Logger utilitly class.
 *
 * @category  Library
 * @package   Logger
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://github.com/appserver-io/logger
 * @link      http://www.appserver.io
 */
class LoggerUtils
{

    /**
     * The key for the system logger.
     *
     * @var string
     */
    const SYSTEM = 'System';

    /**
     * The key for the access logger.
     *
     * @var string
     */
    const ACCESS = 'Access';

    /**
     * The key for the profile logger.
     *
     * @var string
     */
    const PROFILE = 'Profile';

    /**
     * The log levels mapped to comparable integer values.
     *
     * @var array
     */
    public static $logLevels = array(
        LogLevel::DEBUG     => 100, // Detailed debug information.
        LogLevel::INFO      => 200, // Interesting events. Examples: User logs in, SQL logs.
        LogLevel::NOTICE    => 250, // Normal but significant events.
        LogLevel::WARNING   => 300, // Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.
        LogLevel::ERROR     => 400, // Runtime errors that do not require immediate action but should typically be logged and monitored.
        LogLevel::CRITICAL  => 500, // Critical conditions. Example: Application component unavailable, unexpected exception.
        LogLevel::ALERT     => 550, // Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up.
        LogLevel::EMERGENCY => 600, // Emergency: system is unusable.
    );
}

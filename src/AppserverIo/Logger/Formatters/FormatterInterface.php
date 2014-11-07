<?php

/**
 * AppserverIo\Logger\Formatters\FormatterInterface
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
 * @subpackage Formatters
 * @author     Tim Wagner <tw@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://github.com/appserver-io/logger
 * @link       http://www.appserver.io
 */

namespace AppserverIo\Logger\Formatters;

use AppserverIo\Logger\LogMessageInterface;

/**
 * Interface for all formatters.
 *
 * @category   Library
 * @package    Logger
 * @subpackage Formatters
 * @author     Tim Wagner <tw@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://github.com/appserver-io/logger
 * @link       http://www.appserver.io
 */
interface FormatterInterface
{

    /**
     * Formats and returns a string representation of the passed log message.
     *
     * @param \AppserverIo\Logger\Formatters\LogMessageInterface $logMessage The log message we want to format
     *
     * @return string The formatted string representation for the log messsage
     */
    public function format(LogMessageInterface $logMessage);
}

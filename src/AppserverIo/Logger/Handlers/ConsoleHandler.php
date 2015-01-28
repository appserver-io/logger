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

use AppserverIo\Logger\LogMessageInterface;

/**
 * A dummy logger implementation.
 *
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2015 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://github.com/appserver-io/logger
 * @link      http://www.appserver.io
 */
class ConsoleHandler extends DummyHandler
{

    /**
     * Handles the log message.
     *
     * @param \AppserverIo\Logger\LogMessageInterface $logMessage The message to be handled
     *
     * @return void
     */
    public function handle(LogMessageInterface $logMessage)
    {
        if ($this->shouldLog($logMessage->getLevel())) {
            // check the log level
            print $this->getFormatter()->format($logMessage) . PHP_EOL;
        }
    }
}

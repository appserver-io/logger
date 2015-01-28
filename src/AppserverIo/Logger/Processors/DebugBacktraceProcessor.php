<?php

/**
 * AppserverIo\Logger\Processors\DebugBacktraceProcessor
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

namespace AppserverIo\Logger\Processors;

use AppserverIo\Logger\LogMessageInterface;

/**
 * Processor that adds the actual debug backtrace to the message context.
 *
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2015 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://github.com/appserver-io/logger
 * @link      http://www.appserver.io
 */
class DebugBacktraceProcessor implements ProcessorInterface
{

    /**
     * The key for the debug backtrace.
     *
     * @var string
     */
    const CONTEXT_KEY = 'backtrace';

    /**
     * Adds the debug backtrace to the log message context.
     *
     * @param \AppserverIo\Logger\LogMessageInterface $logMessage The log message we want to add the debug backtrace
     *
     * @return string The processed log messsage
     */
    public function process(LogMessageInterface $logMessage)
    {
        $logMessage->appendToContext(DebugBacktraceProcessor::CONTEXT_KEY, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
    }
}

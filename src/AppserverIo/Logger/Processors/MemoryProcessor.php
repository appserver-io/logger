<?php

/**
 * AppserverIo\Logger\Processors\MemoryProcessor
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
 * Processor that adds the actual memory usage to the message context.
 *
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2015 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://github.com/appserver-io/logger
 * @link      http://www.appserver.io
 */
class MemoryProcessor implements ProcessorInterface
{

    /**
     * Adds the memory to the message context.
     *
     * @var string
     */
    const CONTEXT_KEY = 'memory';

    /**
     * Adds the actual memory usage to the log message context.
     *
     * @param \AppserverIo\Logger\LogMessageInterface $logMessage The log message we want to add the memory usage
     *
     * @return string The processed log messsage
     * @see \AppserverIo\Logger\Processors\ProcessorInterface::process()
     */
    public function process(LogMessageInterface $logMessage)
    {
        $logMessage->appendToContext(MemoryProcessor::CONTEXT_KEY, memory_get_usage());
    }
}

<?php

/**
 * AppserverIo\Logger\Processors\ThreadContextProcessor
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
 * @subpackage Processors
 * @author     Tim Wagner <tw@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://github.com/appserver-io/logger
 * @link       http://www.appserver.io
 */

namespace AppserverIo\Logger\Processors;

use AppserverIo\Logger\Logger;
use AppserverIo\Logger\LogMessageInterface;
use AppserverIo\Logger\Processors\ProcessorInterface;

/**
 * Logger processor that adds the thread context as name attribute. The thread context
 * will be queried from the ProfileLogger, to the log context.
 *
 * @category   Library
 * @package    Logger
 * @subpackage Processors
 * @author     Tim Wagner <tw@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://github.com/appserver-io/logger
 * @link       http://www.appserver.io
 */
class ThreadContextProcessor implements ProcessorInterface
{

    /**
     * Key for the context name in the message context.
     *
     * @var string
     */
    const CONTEXT_KEY = 'name';

    /**
     * Adds the thread context as name attribute to the log message context.
     *
     * @param \AppserverIo\Logger\Formatters\LogMessageInterface $logMessage The log message we want to add the thread context name to
     *
     * @return string The processed log messsage
     * @see \AppserverIo\Logger\Processors\ProcessorInterface::process()
     */
    public function process(LogMessageInterface $logMessage)
    {
        $logMessage->appendToContext(ThreadContextProcessor::CONTEXT_KEY, Logger::$threadContext);
    }
}

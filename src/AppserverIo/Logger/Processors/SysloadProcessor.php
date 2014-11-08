<?php

/**
 * AppserverIo\Logger\Processors\SysloadProcessor
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

use AppserverIo\Logger\LogMessageInterface;

/**
 * Processor that adds the actual system load to the message context.
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
class SysloadProcessor implements ProcessorInterface
{

    /**
     * Adds the actual system load to the log message context.
     *
     * @param \AppserverIo\Logger\Formatters\LogMessageInterface $logMessage The log message we want to add the system load
     *
     * @return string The processed log messsage
     * @see \AppserverIo\Logger\Processors\ProcessorInterface::process()
     */
    public function process(LogMessageInterface $logMessage)
    {

        // load the sysload values
        $values = sys_getloadavg();

        // create an array
        $sysload = array(
            'system_load_1'  => array_shift($values),
            'system_load_5'  => array_shift($values),
            'system_load_15' => array_shift($values)
        );

        // merge the values with the actual context instance
        $logMessage->mergeIntoContext($sysload);
    }
}

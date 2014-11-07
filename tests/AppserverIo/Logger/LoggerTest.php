<?php

/**
 * AppserverIo\Logger\LoggerTest
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
 * This is test implementation for logger implementation.
 *
 * @category  Library
 * @package   Routlt
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://github.com/appserver-io/logger
 * @link      http://www.appserver.io
 */
class LoggerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * A test channel name.
     *
     * @var string
     */
    const CHANNEL_NAME = 'test';

    /**
     * The abstract action instance to test.
     *
     * @var \AppserverIo\Logger\Logger
     */
    protected $logger;

    /**
     * Initializes the base action to test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->logger = new Logger(LoggerTest::CHANNEL_NAME);
    }

    /**
     * This test checks for the correct channel name.
     *
     * @return void
     */
    public function testGetChannelName()
    {
        $this->assertSame(LoggerTest::CHANNEL_NAME, $this->logger->getChannelName());
    }
}

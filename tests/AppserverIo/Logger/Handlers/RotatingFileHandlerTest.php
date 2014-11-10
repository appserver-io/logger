<?php

/**
 * AppserverIo\Logger\Handlers\CustomFileHandler
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
 * @subpackage Handlers
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @author     Tim Wagner <tw@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://github.com/appserver-io/logger
 * @link       http://www.appserver.io
 */

namespace AppserverIo\Logger\Handlers;

use Psr\Log\LogLevel;
use AppserverIo\Logger\LogMessage;

/**
 * Test for the rotating log file handler.
 *
 * @category   Library
 * @package    Logger
 * @subpackage Handlers
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @author     Tim Wagner <tw@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://github.com/appserver-io/logger
 * @link       http://www.appserver.io
 */
class RotatingFileHandlerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * A temporary directory for testing purposes
     *
     * @var string TMP_DIR
     */
    const TMP_DIR = '/_files/var/tmp/';

    /**
     * A static directory for testing purposes
     *
     * @var string TMP_DIR
     */
    const STATIC_DIR = '/_files/var/static/';

    /**
     * A temporary file for testing purposes
     *
     * @var string TMP_FILE
     */
    const TMP_FILE = 'test-file.log';

    /**
     * @var  $handler
     */
    protected $handler;

    /**
     * Will return a test record due at a certain date.
     *
     * @return \AppserverIo\Logger\LogMessageInterface The initialize log message
     */
    protected function getRecordByDate()
    {

        // initialize the data for the log message
        $id = uniqid();
        $level = LogLevel::DEBUG;
        $message = 'Provisioner datasource successfully initialized and executed';
        $context = array (
            'file' => '/opt/appserver/app/code/vendor/techdivision/appserver/src/TechDivision/ApplicationServer/Server.php',
            'line' => 288,
            'class' => 'TechDivision\\ApplicationServer\\Server',
            'function' => 'initProvisioners',
        );

        // initialize and return the log message
        return new LogMessage($id, $level, $message, $context);
    }

    /**
     * Initialize the instance to test.
     *
     * @return void
     */
    public function setUp()
    {
        // we need a handler
        $this->handler = new RotatingFileHandler(__DIR__ . self::TMP_DIR . self::TMP_FILE, LogLevel::DEBUG);

        // we assume the log file already exists
        touch(__DIR__ . self::TMP_DIR . self::TMP_FILE);
        clearstatcache();
    }

    /**
     * Cleans the log files we might have created during our tests
     *
     * @return void
     */
    public function tearDown()
    {
        $files = scandir(__DIR__ . self::TMP_DIR);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && $file !== '.gitignore') {
                unlink(__DIR__ . self::TMP_DIR . $file);
            }
        }
        clearstatcache();
    }

    /**
     * Test for the classes "getCurrentSizeIteration" method to count correctly
     *
     * @return void
     */
    public function testGetCurrentSizeIterationNotOver()
    {

        // get a new handler with a very low file size
        $this->handler = new RotatingFileHandler(__DIR__ . self::TMP_DIR . self::TMP_FILE, LogLevel::DEBUG, 0, 10);

        // write two times
        $record = $this->getRecordByDate();
        for ($i = 0; $i < 2; $i++) {
            $this->handler->handle($record);
            clearstatcache();
        }

        $comingSizeIterator = (int) substr(strrchr($this->handler->getRotatedFilename(), "_"), 1, 1);
        $this->assertEquals(2, $comingSizeIterator);
    }

    /**
     * Test for the classes "rotate" method based on a record from the future
     *
     * @return void
     */
    public function testRotateByDate()
    {
        // get the glob pattern and check how the amount of files changes for each write
        $globPattern = $this->handler->getGlobPattern(date($this->handler->getDateFormat()));

        // write once for today
        $record = $this->getRecordByDate();
        $this->handler->handle($record);
        clearstatcache();
        $this->assertEquals(0, count(glob($globPattern)));

        // write once for the future
        $record = $this->getRecordByDate();
        $this->handler->setNextRotationDate(new \DateTime('yesterday'));
        $this->handler->handle($record);
        clearstatcache();
        $rotatedFiles = glob($globPattern);
        $this->assertEquals(1, count($rotatedFiles));
        $this->assertTrue(file_exists(__DIR__ . self::TMP_DIR . self::TMP_FILE));

        // remove the current file and write again to check if we always write to the current file
        foreach ($rotatedFiles as $rotatedFile) {
            unlink($rotatedFile);
        }
        $record = $this->getRecordByDate();
        $this->handler->handle($record);
        $this->assertEquals(0, count(glob($globPattern)));
    }

    /**
     * Test for the classes "rotate" method based on a low maxFiles value
     *
     * @return void
     */
    public function testRotateByMaxFiles()
    {
        // get a new handler with a very low number of maximum files and a low maximal file size
        $this->handler = new RotatingFileHandler(__DIR__ . self::TMP_DIR . self::TMP_FILE, LogLevel::DEBUG, 2, 10);

        // get the glob pattern and check how the amount of files changes for each write
        $globPattern = $this->handler->getGlobPattern(date($this->handler->getDateFormat()));

        // write four times
        $record = $this->getRecordByDate();
        for ($i = 0; $i < 4; $i++) {

            $this->handler->handle($record);
            clearstatcache();
        }
        $this->assertEquals(2, count(glob($globPattern)));

        // get a new handler with a very low number of maximum files and a low maximal file size
        $this->handler = new RotatingFileHandler(__DIR__ . self::TMP_DIR . self::TMP_FILE, LogLevel::DEBUG, 1, 10);

        // write three times
        for ($i = 0; $i < 3; $i++) {

            $this->handler->handle($record);
            clearstatcache();
        }
       $this->assertEquals(1, count(glob($globPattern)));
       $this->assertTrue(file_exists(__DIR__ . self::TMP_DIR . self::TMP_FILE));
    }

    /**
     * Test for the classes "setDateFormat" method
     *
     * @return void
     */
    public function testSetDateFormat()
    {
        $dummyFormat = 'I am not a format at all';
        $this->handler->setDateFormat($dummyFormat);
        $this->assertEquals($dummyFormat, $this->handler->getDateFormat());
    }

    /**
     * Test for the classes "setFilenameFormat" method
     *
     * @return void
     */
    public function testSetFilenameFormat()
    {
        $dummyFormat = 'I am not a format at all';
        $this->handler->setFilenameFormat($dummyFormat);
        $this->assertEquals($dummyFormat, $this->handler->getFilenameFormat());
    }

    /**
     * Test for the classes "write" method with a forced rotation based on the file size
     *
     * @return void
     */
    public function testSizeRotation()
    {
        // get a new handler with a very low file size
        $this->handler = new RotatingFileHandler(__DIR__ . self::TMP_DIR . self::TMP_FILE, LogLevel::DEBUG, 0, 10);

        // get the glob pattern and check how the amount of files changes for each write
        $globPattern = $this->handler->getGlobPattern(date($this->handler->getDateFormat()));

        // create a new record with current date
        $record = $this->getRecordByDate();

        // write three times
        for ($i = 0; $i < 3; $i++) {

            $this->handler->handle($record);
            clearstatcache();
        }
        $this->assertEquals(2, count(glob($globPattern)));
    }

    /**
     * Test for the classes "write" method
     *
     * @return void
     */
    public function testWrite()
    {
        // create a new record with current date and write it
        $record = $this->getRecordByDate();
        $this->handler->handle($record);
        $this->assertTrue(file_exists(__DIR__ . self::TMP_DIR . self::TMP_FILE));
    }

    /**
     * Test for the classes "write" method
     *
     * @return void
     */
    public function testWriteWithoutRotating()
    {
        // get the glob pattern and check how the amount of files changes for each write
        $globPattern = $this->handler->getGlobPattern(date($this->handler->getDateFormat()));
        $initialFileCount = count(glob($globPattern));

        // create a new record with current date
        $record = $this->getRecordByDate();

        // write three times
        for ($i = 0; $i < 3; $i++) {

            $this->handler->handle($record);
            clearstatcache();
        }
        $this->assertEquals($initialFileCount, count(glob($globPattern)));
        $this->assertTrue(file_exists(__DIR__ . self::TMP_DIR . self::TMP_FILE));
    }
}
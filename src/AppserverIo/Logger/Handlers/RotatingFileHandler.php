<?php

/**
 * AppserverIo\Logger\Handlers\RotatingFileHandler
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
 * @author     Tim Wagner <tw@techdivision.com>
 * @copyright  2014 TechDivision GmbH <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://github.com/appserver-io/logger
 * @link       http://www.appserver.io
 */

namespace AppserverIo\Logger\Handlers;

use Psr\Log\LogLevel;
use AppserverIo\Logger\LogMessageInterface;
use AppserverIo\Logger\Handlers\CustomFileHandler;

/**
 * Handler implementation which allows for proper usage within a pthreads environment, as there
 * is a bug denying a call to a protected method within the same hierarchy if the classes are
 * already known by the parent thread context.
 *
 * The class got also extended to support file rotation based on a maximal file size.
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
class RotatingFileHandler extends CustomFileHandler
{

    /**
     * Placeholder for the "date" part of the file format
     *
     * @var string DATE_FORMAT_PLACEHOLDER
     */
    const DATE_FORMAT_PLACEHOLDER = '{date}';

    /**
     * Placeholder for the "original filename" part of the file format
     *
     * @var string FILENAME_FORMAT_PLACEHOLDER
     */
    const FILENAME_FORMAT_PLACEHOLDER = '{filename}';

    /**
     * The maximal possible file size (2Gb).
     * Limited as a precaution due to PHP integer type limitation on x86 systems
     *
     * @var integer MAX_FILE_SIZE
     */
    const MAX_FILE_SIZE = 2147483647;

    /**
     * Placeholder for the "size iterator" part of the file format
     *
     * @var string SIZE_FORMAT_PLACEHOLDER
     */
    const SIZE_FORMAT_PLACEHOLDER = '{sizeIterator}';

    /**
     * Format for the date shown within the rotated filename.
     * E.g. 'Y-m-d'
     *
     * @var string
     */
    protected $dateFormat;

    /**
     * Format of the filename after being rotated
     *
     * @var string
     */
    protected $filenameFormat;

    /**
     * Number of maximal files to keep.
     * Older files exceeding this limit will be deleted
     *
     * @var integer
     */
    protected $maxFiles;

    /**
     * Maximal size a log file might have after rotation gets triggered
     *
     * @var integer
     */
    protected $maxSize;

    /**
     * Whether or not a rotation has to take place at the next possible point in time
     *
     * @var boolean
     */
    protected $mustRotate;

    /**
     * Date at which the next rotation has to take place (if there are no size based rotations before)
     *
     * @var \DateTime
     */
    protected $nextRotationDate;

    /**
     * The original name of the file to rotate
     *
     * @var string
     */
    protected $originalLogFile;

    /**
     * Default constructor
     *
     * @param string       $logFile  Log file base name
     * @param integer      $logLevel The minimum logging level at which this handler will be triggered
     * @param integer      $maxFiles The maximal amount of files to keep (0 means unlimited)
     * @param integer|null $maxSize  The maximal size of a log file in byte (limited to a technical max of 2GB)
     */
    public function __construct($logFile, $logLevel = LogLevel::DEBUG, $maxFiles = 0, $maxSize = null)
    {

        // also construct the parent
        parent::__construct($logFile, $logLevel);

        // get the values passed via constructor
        $this->originalLogFile = $logFile;
        $this->maxFiles = (integer) $maxFiles;

        // also set the maximal size, but make sure we do not exceed the boundary
        if ($maxSize > RotatingFileHandler::MAX_FILE_SIZE || is_null($maxSize)) {
            $maxSize = RotatingFileHandler::MAX_FILE_SIZE;
        }
        // set the maximum size of log files
        $this->maxSize = (int) $maxSize;

        // preset the filename format
        $this->filenameFormat = self::FILENAME_FORMAT_PLACEHOLDER . '-' .
            self::DATE_FORMAT_PLACEHOLDER . '_' .
            self::SIZE_FORMAT_PLACEHOLDER;

        // set some default values
        $this->dateFormat = 'Y-m-d';
        $this->mustRotate = false;
        $this->nextRotationDate = new \DateTime('tomorrow');
    }

    /**
     * Will cleanup log files based on the value set for their maximal number
     *
     * @return void
     */
    protected function cleanupFiles()
    {
        // skip GC of old logs if files are unlimited
        if (0 === $this->maxFiles) {
            return;
        }

        $logFiles = glob($this->getGlobPattern());
        if ($this->maxFiles >= count($logFiles)) { // no files to remove
            return;
        }

        // Sorting the files by name to remove the older ones
        usort(
            $logFiles,
            function ($a, $b) {
                return strcmp($b, $a);
            }
        );

        // collect the files we have to archive and clean and prepare the archive's internal mapping
        $oldFiles = array();
        foreach (array_slice($logFiles, $this->maxFiles) as $oldFile) {
            $oldFiles[basename($oldFile)] = $oldFile;
        }

        // create an archive from the old files
        $dateTime = new \DateTime();
        $currentTime = $dateTime->format($this->getDateFormat());
        $phar = new \PharData($this->originalLogFile . $currentTime .  '.tar');
        $phar->buildFromIterator(new \ArrayIterator($oldFiles));

        // finally delete them as we got them in the archive
        foreach ($oldFiles as $oldFile) {
            if (is_writable($oldFile)) {
                unlink($oldFile);
            }
        }
    }

    /**
     * Will close the handler
     *
     * @return void
     */
    public function close()
    {
        // might do a rotation before closing
        if (true === $this->mustRotate) {
            $this->rotate();
        }
    }

    /**
     * Sets the next rotation date.
     *
     * @param \DateTimeInterface $nextRotationDate The next rotation date
     *
     * @return void
     */
    public function setNextRotationDate(\DateTimeInterface $nextRotationDate)
    {
        $this->nextRotationDate = $nextRotationDate;
    }

    /**
     * Will return the name of the file the next rotation will produce
     *
     * @return string
     */
    public function getRotatedFilename()
    {
        $fileInfo = pathinfo($this->logFile);
        $currentFilename = str_replace(
            array(
                self::FILENAME_FORMAT_PLACEHOLDER,
                self::DATE_FORMAT_PLACEHOLDER,
                self::SIZE_FORMAT_PLACEHOLDER
            ),
            array(
                $fileInfo['filename'],
                date($this->dateFormat),
                $this->getCurrentSizeIteration() // $this->currentSizeIteration
            ),
            $fileInfo['dirname'] . '/' . $this->filenameFormat
        );
        if (!empty($fileInfo['extension'])) {
            $currentFilename .= '.' . $fileInfo['extension'];
        }
        return $currentFilename;
    }

    /**
     * Will return the currently used iteration based on a file's size.
     *
     * @return integer The number of logfiles already exists
     */
    protected function getCurrentSizeIteration()
    {

        // load an iterator the current log files
        $logFiles = glob($this->getGlobPattern(date($this->dateFormat)));

        $fileCount = count($logFiles); // count the files
        if ($fileCount === 0) {
            return 1;
        } else {
            return count($logFiles) + 1;
        }
    }

    /**
     * Getter for the date format to store the logfiles under.
     *
     * @return string The date format to store the logfiles under
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     * Getter for the file format to store the logfiles under.
     *
     * @return string The file format to store the logfiles under
     */
    public function getFilenameFormat()
    {
        return $this->filenameFormat;
    }

    /**
     * Will return a glob pattern with which log files belonging to the currently rotated file can be found
     *
     * @param string|\DateTime $dateSpecifier Might specify a specific date to search files for
     *
     * @return string
     */
    public function getGlobPattern($dateSpecifier = '*')
    {

        // load the file information
        $fileInfo = pathinfo($this->logFile);

        // create a glob expression to find all log files
        $glob = str_replace(
            array(
                self::FILENAME_FORMAT_PLACEHOLDER,
                self::DATE_FORMAT_PLACEHOLDER,
                self::SIZE_FORMAT_PLACEHOLDER
            ),
            array(
                $fileInfo['filename'],
                $dateSpecifier,
                '*'
            ),
            $fileInfo['dirname'] . '/' . $this->filenameFormat
        );

        // append the file extension if available
        if (empty($fileInfo['extension']) === false) {
            $glob .= '.' . $fileInfo['extension'];
        }

        // return the glob expression
        return $glob;
    }

    /**
     * Does the rotation of the log file which includes updating the currently used filename as well as cleaning up
     * the log directory
     *
     * @return void
     */
    protected function rotate()
    {
        // update filename
        rename($this->logFile, $this->getRotatedFilename());

        $this->nextRotationDate = new \DateTime('tomorrow');
        $this->mustRotate = false;
    }

    /**
     * Setter for the date format
     *
     * @param string $dateFormat Form that date will be shown in
     *
     * @return void
     */
    public function setDateFormat($dateFormat)
    {
        $this->dateFormat = $dateFormat;
        $this->close();
    }

    /**
     * Setter for the file format
     * If setting this please make use of the defined format placeholder constants
     *
     * @param string $filenameFormat New format to be used
     *
     * @return void
     */
    public function setFilenameFormat($filenameFormat)
    {
        $this->filenameFormat = $filenameFormat;
        $this->close();
    }

    /**
     * Handles the log message.
     *
     * @param \AppserverIo\Logger\LogMessageInterface $logMessage The message to be handled
     *
     * @return void
     */
    public function handle(LogMessageInterface $logMessage)
    {

        // do we have to rotate based on the current date or the file's size?
        if ($this->nextRotationDate < new \DateTime()) {
            $this->mustRotate = true;
            $this->close();
        } elseif (file_exists($this->logFile) && filesize($this->logFile) >= $this->maxSize) {
            $this->mustRotate = true;
            $this->close();
        }

        // let the parent class handle the log message
        parent::handle($logMessage);

        // cleanup the files we might not want
        $this->cleanupFiles();
    }
}

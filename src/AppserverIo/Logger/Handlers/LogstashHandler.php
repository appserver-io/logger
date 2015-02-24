<?php

/**
 * AppserverIo\Logger\Handlers\LogstashHandler
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

use Psr\Log\LogLevel;
use AppserverIo\Logger\LogMessageInterface;
use AppserverIo\Logger\Formatters\JsonFormatter;

/**
 * A logstash handler implementation that sends the log message to
 * Logstash using a UDP connection.
 *
 * @author    Tim Wagner <tw@techdivision.com>
 * @copyright 2015 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://github.com/appserver-io/logger
 * @link      http://www.appserver.io
 */
class LogstashHandler extends DummyHandler
{

    /**
     * The logstash host.
     *
     * @var string
     */
    protected $host;

    /**
     * The logstash port.
     *
     * @var integer
     */
    protected $port;

    /**
     * Default constructor
     *
     * @param string  $host     The logstash host name/IP address
     * @param integer $port     The logstash UDP port
     * @param integer $logLevel The minimum logging level at which this handler will be triggered
     */
    public function __construct($host = '127.0.0.1', $port = 9514, $logLevel = LogLevel::DEBUG)
    {

        // call parent constructor
        parent::__construct($logLevel);

        // set the JSON formatter
        $this->formatter = new JsonFormatter();

        // initialize the variables
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * Returns the lostash host.
     *
     * @return string The logstash host
     */
    protected function getHost()
    {
        return $this->host;
    }

    /**
     * Returns the logstash port.
     *
     * @return integer The logstash port
     */
    protected function getPort()
    {
        return $this->port;
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
        if ($this->shouldLog($logMessage->getLevel())) {
            // check the log level

            // the JSON encoded log message
            $jsonMessage = $this->getFormatter()->format($logMessage);

            // initialize a UDP socket
            $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

            // connect, write the message and close the socket
            socket_connect($socket, $this->getHost(), $this->getPort());
            socket_write($socket, $jsonMessage, strlen($jsonMessage));
            socket_close($socket);
        }
    }
}

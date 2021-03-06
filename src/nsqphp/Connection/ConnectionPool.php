<?php

namespace nsqphp\Connection;

use nsqphp\Exception\ConnectionException;
use nsqphp\Exception\SocketException;

/**
 * Represents a pool of connections to one or more NSQD servers
 */
class ConnectionPool implements \Iterator, \Countable
{
    /**
     * Connections
     * 
     * @var array [] = ConnectionInterface $connection
     */
    private $connections = array();

    /**
     * Add connection
     * 
     * @param ConnectionInterface $connection
     */
    public function add(ConnectionInterface $connection)
    {
        $this->connections[] = $connection;
    }

    /**
     * remove connection from connection pool
     *
     * @param Resource|string $socketOrHost
     *
     */
    public function remove($socketOrHost)
    {
        foreach ($this->connections as $idx => $conn) {
            if (is_string($socketOrHost) && (string)$conn === $socketOrHost) {
                unset($this->connections[$idx]);
            } elseif ($conn->getSocket() === $socketOrHost) {
                unset($this->connections[$idx]);
            }
        }
    }

    /**
     * Test if has connection
     * 
     * Remember that the sockets are lazy-initialised so we can create
     * connection instances to test with without incurring a socket connection.
     * 
     * @param ConnectionInterface $connection
     * 
     * @return boolean
     */
    public function hasConnection(ConnectionInterface $connection)
    {
        return $this->find($connection->getSocket()) ? TRUE : FALSE;
    }
    
    /**
     * Find connection from socket/host
     * 
     * @param Resource|string $socketOrHost
     * 
     * @return ConnectionInterface|NULL Will return NULL if not found
     */
    public function find($socketOrHost)
    {
        foreach ($this->connections as $conn) {
            if (is_string($socketOrHost) && (string)$conn === $socketOrHost) {
                return $conn;
            } elseif ($conn->getSocket() === $socketOrHost) {
                return $conn;
            }
        }
        return NULL;
    }
    
    /**
     * Get key of current item as string
     *
     * @return string
     */
    public function key()
    {
        return key($this->connections);
    }

    /**
     * Test if current item valid
     *
     * @return boolean
     */
    public function valid()
    {
        return (current($this->connections) === FALSE) ? FALSE : TRUE;
    }

    /**
     * Fetch current value
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->connections);
    }

    /**
     * Go to next item
     */
    public function next()
    {
        next($this->connections);
    }

    /**
     * Rewind to start
     */
    public function rewind()
    {
        reset($this->connections);
    }

    /**
     * Move to end
     */
    public function end()
    {
        end($this->connections);
    }

    /**
     * Get count of items
     *
     * @return integer
     */
    public function count()
    {
        return count($this->connections);
    }
    
    /**
     * Shuffle connections
     */
    public function shuffle()
    {
        shuffle($this->connections);
    }
}
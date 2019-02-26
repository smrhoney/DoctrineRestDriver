<?php
/**
 * This file is part of DoctrineRestDriver.
 *
 * DoctrineRestDriver is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DoctrineRestDriver is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DoctrineRestDriver.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Circle\DoctrineRestDriver;

use Circle\DoctrineRestDriver\Annotations\RoutingTable;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Driver\Connection;
use Circle\DoctrineRestDriver\Events\PrepareStatementArgs;
use Circle\DoctrineRestDriver\Events\RestDriverEvents as Events;

/**
 * Doctrine connection for the rest driver
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 */
class RestConnection implements Connection {

    /**
     * @var Statement
     */
    private $statement;

    /**
     * @var array
     */
    private $routings;
    /**
     * @var Driver
     */
    private $driver;

    private $eventManager;
    private $params;

    protected $defaultFetchMode = \PDO::FETCH_ASSOC;

    /**
     * Connection constructor
     *
     * @param array $params
     * @param Driver $driver
     * @param RoutingTable $routings
     * @param EventManager|null $eventManager
     */
    public function __construct(array $params, Driver $driver, RoutingTable $routings,  EventManager $eventManager = null) {
        $this->params = $params;
        $this->driver = $driver;
        $this->routings = $routings;
        $this->eventManager = $eventManager;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return Driver
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param Driver $driver
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;
    }


    /**
     * prepares the statement execution
     *
     * @param  string $statement
     * @return Statement
     * @throws Validation\Exceptions\NotNilException
     */
    public function prepare($statement) {

        $args = new PrepareStatementArgs([
            'statement' => $statement,
            'params' => $this->getParams(),
            'routings' => $this->routings,
            'metaData' => $this->getDriver()->getMetaData(),
            'eventManager' => $this->eventManager,
        ]);


        $this->eventManager->dispatchEvent(Events::PREPARE_STATEMENT, $args);
        $this->statement = $args->getRestStatement();


        //$this->statement = new Statement($statement, $this->getParams(), $this->routings, $this->getDriver()
           // ->getMetaData());
        $this->statement->setFetchMode($this->defaultFetchMode);

        return $this->statement;
    }

    /**
     * returns the last inserted id
     *
     * @param  string|null $seqName
     * @return int
     *
     * @SuppressWarnings("PHPMD.UnusedFormalParameter")
     */
    public function lastInsertId($seqName = null) {
        return $this->statement->getId();
    }

    /**
     * Executes a query, returns a statement
     *
     * @return Statement
     * @throws Exceptions\RequestFailedException
     * @throws Validation\Exceptions\NotNilException
     */
    public function query() {
        $statement = $this->prepare(func_get_args()[0]);
        $statement->execute();

        return $statement;
    }

    /**
     * @inheritDoc
     */
    function quote($input, $type = \PDO::PARAM_STR)
    {
        // TODO: Implement quote() method.
    }

    /**
     * @inheritDoc
     */
    function exec($statement)
    {
        // TODO: Implement exec() method.
    }

    /**
     * @inheritDoc
     */
    function beginTransaction()
    {
        // TODO: Implement beginTransaction() method.
    }

    /**
     * @inheritDoc
     */
    function commit()
    {
        // TODO: Implement commit() method.
    }

    /**
     * @inheritDoc
     */
    function rollBack()
    {
        // TODO: Implement rollBack() method.
    }

    /**
     * @inheritDoc
     */
    function errorCode()
    {
        // TODO: Implement errorCode() method.
    }

    /**
     * @inheritDoc
     */
    function errorInfo()
    {
        // TODO: Implement errorInfo() method.
    }
}
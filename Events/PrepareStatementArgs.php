<?php
/**
 * Created by PhpStorm.
 * User: srhoney
 * Date: 2/25/2019
 * Time: 3:12 PM
 */

namespace Circle\DoctrineRestDriver\Events;


use Circle\DoctrineRestDriver\Annotations\RoutingTable;
use Circle\DoctrineRestDriver\Statement;
use Doctrine\Common\EventArgs;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Driver\Statement as StatementInterface;


class PrepareStatementArgs extends EventArgs
{
    /** @var string */
    private $statement;

    private $restStatement;
    /** @var array */
    private $params;

    private $routings;

    private $metaData;

    private $eventManager;

    public function __construct(array $options)
    {
        foreach ($options as $key => $value) {
            $setter = 'set' . ucfirst($key);
            $this->$setter($value);
        }
    }

    /**
     * @return string
     */
    public function getStatement()
    {
        return $this->statement;
    }

    /**
     * @param string $statement
     */
    public function setStatement($statement)
    {
        $this->statement = $statement;
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
     * @return RoutingTable
     */
    public function getRoutings()
    {
        return $this->routings;
    }

    /**
     * @param RoutingTable $routings
     */
    public function setRoutings(RoutingTable $routings)
    {
        $this->routings = $routings;
    }

    /**
     * @return mixed
     */
    public function getMetaData()
    {
        return $this->metaData;
    }

    /**
     * @param mixed $metaData
     */
    public function setMetaData($metaData)
    {
        $this->metaData = $metaData;
    }

    /**
     * @return mixed
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * @param mixed $eventManager
     */
    public function setEventManager(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    public function setRestStatement(StatementInterface $statement)
    {
        return $this->restStatement = $statement;
    }

    /**
     * @return Statement
     * @throws \Circle\DoctrineRestDriver\Validation\Exceptions\NotNilException
     * return StatementInterface
     */
    public function getRestStatement()
    {
        if (! $this->restStatement) {
           return new Statement(
               $this->getStatement(),
               $this->getParams(),
               $this->getRoutings(),
               $this->getMetaData(),
               $this->getEventManager()
               );
        }
        return $this->restStatement;
    }
}
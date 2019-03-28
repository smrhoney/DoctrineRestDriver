<?php
/**
 * Created by PhpStorm.
 * User: srhoney
 * Date: 2/19/2019
 * Time: 9:52 AM
 */

namespace Circle\DoctrineRestDriver;


use Circle\DoctrineRestDriver\Events\EventManagerAware;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Event;

/**
 * @inheritdoc
 */
class Wrapper extends \Doctrine\DBAL\Connection
{
    /**
     * @var EventManager
     */
    private $_eventManager;
    /**
     * @inheritDoc
     */
    public function __construct(array $params, Driver $driver, Configuration $config = null, EventManager $eventManager = null)
    {
        $this->_eventManager = $eventManager;
        parent::__construct($params, $driver, $config, $eventManager);
    }

    /**
     * @inheritdoc
     */
    public function connect()
    {

        if ($this->_eventManager->hasListeners('preConnect')) {
            $eventArgs = new Event\ConnectionEventArgs($this);
            $this->_eventManager->dispatchEvent('preConnect', $eventArgs);
        }
        return parent::connect();
    }
}

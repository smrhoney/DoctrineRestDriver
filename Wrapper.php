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

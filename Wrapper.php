<?php
/**
 * Created by PhpStorm.
 * User: srhoney
 * Date: 2/19/2019
 * Time: 9:52 AM
 */

namespace Circle\DoctrineRestDriver;

use Doctrine\DBAL\Connection as DbalConnection;
use Doctrine\DBAL\Event as DbalEvent;

/**
 * @inheritdoc
 */
class Wrapper extends DbalConnection
{
    /**
     * @inheritdoc
     */
    public function connect()
    {

        if ($this->_eventManager->hasListeners('preConnect')) {
            $eventArgs = new DbalEvent\ConnectionEventArgs($this);
            $this->_eventManager->dispatchEvent('preConnect', $eventArgs);
        }
        return parent::connect();
    }
}

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

/**
 * @inheritdoc
 */
class Wrapper extends \Doctrine\DBAL\Connection
{
    /**
     * @inheritdoc
     */
    public function __construct(
        array $params,
        Driver $driver,
        Configuration $config = null,
        EventManager $eventManager = null
    )
    {
        parent::__construct($params, $driver, $config, $eventManager);

        if ($driver instanceof EventManagerAware)
            $driver->setEventManager($this->getEventManager());
    }
}

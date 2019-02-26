<?php
/**
 * Created by PhpStorm.
 * User: srhoney
 * Date: 2/19/2019
 * Time: 10:41 AM
 */

namespace Circle\DoctrineRestDriver\Events;


use Circle\DoctrineRestDriver\Driver;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;

class DriverSubscriber implements EventSubscriber
{
    /**
     * @var Driver
     */
    private $driver;

    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata,

        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $args){
        $this->driver->setMetaData($args->getEntityManager()->getMetadataFactory());
    }


}
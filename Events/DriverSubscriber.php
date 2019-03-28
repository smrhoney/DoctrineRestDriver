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
use Doctrine\DBAL\Event\ConnectionEventArgs;
use Doctrine\ORM\EntityManager;

/**
 * Class DriverSubscriber
 * @package Circle\DoctrineRestDriver\Events
 */
class DriverSubscriber implements EventSubscriber
{


    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * DriverSubscriber constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents()
    {
        return [
            'preConnect',
        ];
    }

    public function loadClassMetadata(ConnectionEventArgs $args){
        $driver = $args->getDriver();
        if ( $driver instanceof Driver) {
            $driver->setMetaData($this->entityManager->getMetadataFactory());
            $driver->setEventManager($this->entityManager->getEventManager());
        }
    }

}
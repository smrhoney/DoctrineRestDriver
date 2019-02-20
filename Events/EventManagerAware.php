<?php
/**
 * Created by PhpStorm.
 * User: srhoney
 * Date: 2/19/2019
 * Time: 9:58 AM
 */

namespace Circle\DoctrineRestDriver\Events;


use Doctrine\Common\EventManager;

interface EventManagerAware
{
    /**
     * Sets EventManager
     * @param EventManager $eventManager
     */
    public function setEventManager(EventManager $eventManager);

    /**
     * Returns EventManager
     * @return EventManager
     */
    public function getEventManager();
}
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
use Circle\DoctrineRestDriver\Events\DriverSubscriber;
use Circle\DoctrineRestDriver\Events\EventManagerAware;
use Doctrine\Common\EventManager;
use Doctrine\Common\Persistence\Mapping\AbstractClassMetadataFactory;
use Doctrine\DBAL\Driver as DriverInterface;
use Doctrine\DBAL\Connection as AbstractConnection;
use Doctrine\DBAL\Events;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Schema\MySqlSchemaManager;

/**
 * Rest driver class
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 */
class Driver implements DriverInterface, EventManagerAware {

    /**
     * @var RestConnection
     */
    private $connection;
    /**
     * @var EventManager
     */
    private $eventManager;
    /**
     * @var MetaData
     */
    private $metaData;

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function connect(array $params, $username = null, $password = null, array $driverOptions = array()) {
        if (!empty($this->connection)) return $this->connection;

        if (! $this->metaData) {
            throw new \RuntimeException("Missing required metadata");
        }

        //$this->getEventManager()->hasListeners('preConnect');
        //$this->getEventManager()->dispatchEvent()

        $this->connection = new RestConnection(
            $params,
            $this,
            new RoutingTable($this->metaData->getEntityNamespaces()),
            $this->getEventManager()
        );
        return $this->connection;
    }

    public function setMetaData(AbstractClassMetadataFactory $data){
        $this->metaData = new MetaData($data);
    }

    public function getMetaData()
    {
        return $this->metaData;
    }

    /**
     * {@inheritdoc}
     */
    public function getDatabasePlatform() {
        return new MySqlPlatform();
    }

    /**
     * {@inheritdoc}
     */
    public function getSchemaManager(AbstractConnection $conn) {
        return new MySqlSchemaManager($conn);
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'circle_rest';
    }

    /**
     * {@inheritdoc}
     */
    public function getDatabase(AbstractConnection $conn) {
        return 'rest_database';
    }

    /**
     * @inheritDoc
     */
    public function setEventManager(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * @inheritDoc
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }


}
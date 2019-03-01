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

namespace Circle\DoctrineRestDriver\Transformers;

use Circle\DoctrineRestDriver\Annotations\RoutingTable;
use Circle\DoctrineRestDriver\Enums\HttpMethods;
use Circle\DoctrineRestDriver\Events\ConstructionArgs;
use Circle\DoctrineRestDriver\Factory\RequestFactory;
use Circle\DoctrineRestDriver\MetaData;
use Circle\DoctrineRestDriver\Types\Annotation;
use Circle\DoctrineRestDriver\Types\Id;
use Circle\DoctrineRestDriver\Types\Request;
use Circle\DoctrineRestDriver\Types\SqlOperation;
use Circle\DoctrineRestDriver\Types\SqlQuery;
use Circle\DoctrineRestDriver\Types\Table;
use Circle\DoctrineRestDriver\Types\Url;
use Circle\DoctrineRestDriver\Validation\Assertions;
use Doctrine\Common\EventManager;
use Doctrine\ORM\Mapping\NamedQuery;
use PHPSQLParser\PHPSQLParser;
use Circle\DoctrineRestDriver\Events\RestDriverEvents as Events;

/**
 * Transforms a given sql query to a corresponding request
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 */
class MysqlToRequest {

    /**
     * @var PHPSQLParser
     */
    private $parser;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var array
     */
    private $options;

    /**
     * @var RoutingTable
     */
    private $routings;
    /**
     * @var MetaData
     */
    private $metaData;
    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * MysqlToRequest constructor
     *
     * @param array $options
     * @param RoutingTable $routings
     * @param MetaData $metaData
     * @param EventManager $eventManager
     */
    public function __construct(
        array $options,
        RoutingTable $routings,
        MetaData $metaData,
        EventManager $eventManager = null
    ) {
        $this->eventManager   = $eventManager;

        $this->options        = $options;
        $this->routings       = $routings;
        $this->metaData       = $metaData;

        $this->createParser();
        $this->createRequestFactory();
    }


    protected function createParser(){
        if ($this->eventManager) {
            $args = new ConstructionArgs(PHPSQLParser::class, []);
            $this->eventManager->dispatchEvent(Events::CREATE_SQL_PARSER,$args);
            $this->parser = $args->getObject();
        } else {
            $this->parser = new PHPSQLParser();
        }
    }

    protected function createRequestFactory(){
        if ($this->eventManager) {
            $args = new ConstructionArgs(RequestFactory::class, []);
            $this->eventManager->dispatchEvent(Events::CREATE_REQUEST_FACTORY,$args);
            $this->requestFactory = $args->getObject();
        } else {
            $this->requestFactory = new RequestFactory();
        }
    }


    /**
     * Transforms the given query into a request object
     *
     * @param  string $query
     * @return Request
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function transform($query) {
        $usePatch = isset($this->options['driverOptions']['use_patch']) ? $this->options['driverOptions']['use_patch'] : false;
        
        $tokens     = $this->parser->parse($query);
        $entity     = Table::create($tokens);
        $method     = HttpMethods::ofSqlOperation(SqlOperation::create($tokens), $usePatch);

        if ($qName = Annotation::getQueryName($this->routings, $entity, $query)) {
            $annotation = Annotation::getNamedRoute($this->routings, $entity, $qName);
        }

        if (empty($annotation)) {
            $annotation = Annotation::get($this->routings, $entity, $method);
        }

        $method = str_replace('All', '', $method);
        return $this->requestFactory->createOne($method, $tokens, $this->options, $this->metaData, $annotation);
    }
}
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

namespace Circle\DoctrineRestDriver\Annotations;

use Doctrine\ORM\Mapping\NamedNativeQuery;

/**
 * Contains routing information about a specific entity
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 */
class Routing {

    /**
     * @var Insert
     */
    private $post;

    /**
     * @var Update
     */
    private $put;

    /**
     * @var Update
     */
    private $patch;

    /**
     * @var Select
     */
    private $get;

    /**
     * @var Delete
     */
    private $delete;

    /**
     * @var Fetch
     */
    private $getAll;

    /**
     * @var NamedRoutes
     */
    private $namedRoutes;

    /**
     * @var \Doctrine\ORM\Mapping\NamedNativeQueries
     */
    private $namedNativeQueries;

    /**
     * @var array
     */
    private static $annotations = [
        'post'   => 'Circle\DoctrineRestDriver\Annotations\Insert',
        'put'    => 'Circle\DoctrineRestDriver\Annotations\Update',
        'patch'  => 'Circle\DoctrineRestDriver\Annotations\Update',
        'get'    => 'Circle\DoctrineRestDriver\Annotations\Select',
        'delete' => 'Circle\DoctrineRestDriver\Annotations\Delete',
        'getAll' => 'Circle\DoctrineRestDriver\Annotations\Fetch',
        'namedRoutes'  => 'Circle\DoctrineRestDriver\Annotations\NamedRoutes',
        'namedNativeQueries' => 'Doctrine\ORM\Mapping\NamedNativeQueries'
    ];

    /**
     * Routing constructor
     *
     * @param string $namespace
     */
    public function __construct($namespace) {
        $reader = new Reader();
        $class  = new \ReflectionClass($namespace);

        foreach (self::$annotations as $alias => $annotation) $this->$alias = $reader->read($class, $annotation);
    }

    /**
     * returns the post route
     *
     * @return \Circle\DoctrineRestDriver\Annotations\Insert|null
     */
    public function post() {
        return $this->post;
    }

    /**
     * returns the get route
     *
     * @return \Circle\DoctrineRestDriver\Annotations\Select|null
     */
    public function get() {
        return $this->get;
    }

    /**
     * returns the put route
     *
     * @return \Circle\DoctrineRestDriver\Annotations\Update|null
     */
    public function put() {
        return $this->put;
    }

    /**
     * returns the patch route
     *
     * @return \Circle\DoctrineRestDriver\Annotations\Update|null
     */
    public function patch() {
        return $this->patch;
    }

    /**
     * returns the delete route
     *
     * @return \Circle\DoctrineRestDriver\Annotations\Delete|null
     */
    public function delete() {
        return $this->delete;
    }

    /**
     * returns the get all route
     *
     * @return \Circle\DoctrineRestDriver\Annotations\Fetch|null
     */
    public function getAll() {
        return $this->getAll;
    }

    /**
     * @return NamedRoute[]
     */
    public function namedRoutes() {
        return $this->namedRoutes ? $this->namedRoutes->value : [];
    }

    /**
     * @return NamedNativeQuery[]
     */
    public function namedNativeQueries() {
        return $this->namedNativeQueries ? $this->namedNativeQueries->value : [];
    }
}
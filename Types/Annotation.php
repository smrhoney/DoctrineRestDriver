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

namespace Circle\DoctrineRestDriver\Types;

use Circle\DoctrineRestDriver\Annotations\DataSource;
use Circle\DoctrineRestDriver\Annotations\RoutingTable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * Extracts id information from a sql token array
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 */
class Annotation {

    /**
     * returns the corresponding data source annotation if exists
     *
     * @param  RoutingTable $annotations
     * @param  string       $entityAlias
     * @param  string       $method
     * @return DataSource|null
     */
    public static function get(RoutingTable $annotations, $entityAlias, $method) {
        if (!self::exists($annotations, $entityAlias, $method)) return null;

        return $annotations->get($entityAlias)->$method();
    }

    /**
     * checks if the annotation exists
     *
     * @param  RoutingTable $annotations
     * @param  string       $entityAlias
     * @param  string       $method
     * @return boolean
     */
    public static function exists(RoutingTable $annotations = null, $entityAlias, $method) {
        return !empty($annotations) && $annotations->get($entityAlias) !== null && $annotations->get($entityAlias)->$method() !== null;
    }


    /**
     * returns the corresponding named data source annotation if exists
     *
     * @param  RoutingTable $annotations
     * @param  string       $entityAlias
     * @param  string       $name
     * @return DataSource|null
     */
    public static function getNamedRoute(RoutingTable $annotations, $entityAlias, $name) {
        $routes = new ArrayCollection($annotations->get($entityAlias)->namedRoutes());

        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq("name", $name));

        $matches = $routes->matching($criteria);
        if ($matches->isEmpty()) return null;
        return $matches->first();
    }

    /**
     * returns
     * @param RoutingTable $annotations
     * @param $entityAlias
     * @param $query
     * @return mixed|null
     */
    public static function getQueryName(RoutingTable $annotations, $entityAlias, $query) {
        $queries = new ArrayCollection($annotations->get($entityAlias)->namedNativeQueries());

        if (preg_match('/^--#([A-z]+)/', $query, $matches)) {
            return $matches[1];
        }

        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq("query", $query));

        $matches = $queries->matching($criteria);
        if ($matches->isEmpty()) return null;
        /** @var \Doctrine\ORM\Mapping\NamedNativeQuery $query */
        $query = $matches->first();
        return $query->name;
    }
}
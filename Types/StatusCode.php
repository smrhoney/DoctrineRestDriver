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
use Circle\DoctrineRestDriver\Validation\Assertions;

/**
 * Type class for status codes
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 */
class StatusCode {

    /**
     * @var array
     */
    private static $expectedStatusCodes = [
        'get'    => [200, 203, 206, 404],
        'put'    => [200, 202, 203, 204, 205, 404],
        'patch'  => [200, 202, 203, 204, 205, 404],
        'post'   => [200, 201, 202, 203, 204, 205],
        'delete' => [200, 202, 203, 204, 205, 404]
    ];

    /**
     * returns the status code depending on its input
     *
     * @param  string     $method
     * @param  DataSource $annotation
     * @return string
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public static function create($method, DataSource $annotation = null) {
        Str::assert($method, 'method');

        $annotationStatusCodes = !empty($annotation) && $annotation->getStatusCode() !== null ? (array) $annotation->getStatusCode() : array();
        return $annotationStatusCodes ?: self::$expectedStatusCodes[$method];
    }
}
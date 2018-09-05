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

/**
 * Contract for all data source annotations
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 */
interface DataSource {

    /**
     * returns the route
     *
     * @return string
     */
    public function getRoute();

    /**
     * returns the status code
     *
     * @return int[]|null
     */
    public function getStatusCodes();

    /**
     * returns the method
     *
     * @return null|string
     */
    public function getMethod();

    /**
     * returns the options
     *
     * @return array|null
     */
    public function getOptions();
}
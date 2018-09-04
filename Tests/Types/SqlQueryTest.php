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

namespace Circle\DoctrineRestDriver\Tests\Types;

use Circle\DoctrineRestDriver\Types\SqlQuery;

/**
 * Tests the sql query type
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @coversDefaultClass Circle\DoctrineRestDriver\Types\SqlQuery
 */
class SqlQueryTest extends \PHPUnit\Framework\TestCase {

    /**
     * @test
     * @group  unit
     * @covers ::setParams
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function setParams() {
        $query  = 'SELECT name FROM products WHERE id=? AND name=?';
        $params = [
            1,
            'myName'
        ];
        $expected = 'SELECT name FROM products WHERE id=1 AND name=myName';
        $this->assertSame($expected, SqlQuery::setParams($query, $params));
    }

    /**
     * @test
     * @group  unit
     * @covers ::quoteUrl
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function quoteUrl() {
        $query    = 'SELECT name FROM http://www.circle.ai';
        $expected = 'SELECT name FROM "http://www.circle.ai"';
        $this->assertSame($expected, SqlQuery::quoteUrl($query));
    }
}

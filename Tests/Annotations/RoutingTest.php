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

namespace Circle\DoctrineRestDriver\Tests\Annotations;

use Circle\DoctrineRestDriver\Annotations\Delete;
use Circle\DoctrineRestDriver\Annotations\Select;
use Circle\DoctrineRestDriver\Annotations\Fetch;
use Circle\DoctrineRestDriver\Annotations\Insert;
use Circle\DoctrineRestDriver\Annotations\Update;
use Circle\DoctrineRestDriver\Annotations\Routing;

/**
 * Tests the routing bag
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @coversDefaultClass Circle\DoctrineRestDriver\Annotations\Routing
 */
class RoutingTest extends \PHPUnit\Framework\TestCase {

    /**
     * {@inheritdoc}
     */
    public function setUp() {
        $this->routing = new Routing('Circle\DoctrineRestDriver\Tests\Entity\AssociatedEntity');
    }

    /**
     * @test
     * @group  unit
     * @covers ::__construct
     * @covers ::post
     */
    public function post() {
        $this->assertSame(null, $this->routing->post());
    }

    /**
     * @test
     * @group  unit
     * @covers ::__construct
     * @covers ::put
     */
    public function put() {
        $this->assertSame(null, $this->routing->put());
    }

    /**
     * @test
     * @group  unit
     * @covers ::__construct
     * @covers ::patch
     */
    public function patch() {
        $this->assertSame(null, $this->routing->patch());
    }

    /**
     * @test
     * @group  unit
     * @covers ::__construct
     * @covers ::get
     */
    public function get() {
        $this->assertSame(null, $this->routing->get());
    }

    /**
     * @test
     * @group  unit
     * @covers ::__construct
     * @covers ::delete
     */
    public function delete() {
        $this->assertSame(null, $this->routing->delete());
    }

    /**
     * @test
     * @group  unit
     * @covers ::__construct
     * @covers ::getAll
     */
    public function getAll() {
        $this->assertSame(null, $this->routing->getAll());
    }
}

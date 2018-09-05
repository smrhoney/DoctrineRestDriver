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

use Circle\DoctrineRestDriver\Types\MaybeList;

/**
 * Tests the maybe list type
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @coversDefaultClass Circle\DoctrineRestDriver\Types\MaybeList
 */
class MaybeListTest extends \PHPUnit\Framework\TestCase {

    /**
     * @test
     * @group  unit
     * @covers ::assert
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function assert() {
        $this->assertSame(null, MaybeList::assert(null, 'null'));
        $this->assertSame([], MaybeList::assert([], 'list'));
    }

    /**
     * @test
     * @group  unit
     * @covers ::assert
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     * @expectedException \Circle\DoctrineRestDriver\Validation\Exceptions\InvalidTypeException
     */
    public function assertOnException() {
        MaybeList::assert('hello', 'string');
    }
}

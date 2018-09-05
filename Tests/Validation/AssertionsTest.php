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

namespace Circle\DoctrineRestDriver\Tests\Validation;

use Circle\DoctrineRestDriver\Validation\Assertions;

/**
 * Tests the driver
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @coversDefaultClass Circle\DoctrineRestDriver\Validation\Assertions
 */
class AssertionsTest extends \PHPUnit\Framework\TestCase {

    /**
     * @test
     * @group  unit
     * @covers ::assertClassExists
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function assertClassExistsTest() {
        $this->assertSame(null, Assertions::assertClassExists('Circle\DoctrineRestDriver\Tests\Validation\AssertionsTest'));
    }

    /**
     * @test
     * @group  unit
     * @covers ::assertSupportedFetchMode
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function assertSupportedFetchModeTest() {
        $this->assertSame(\PDO::FETCH_ASSOC, Assertions::assertSupportedFetchMode(\PDO::FETCH_ASSOC));
    }
}

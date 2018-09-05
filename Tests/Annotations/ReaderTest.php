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

use Circle\DoctrineRestDriver\Annotations\Reader;
use Circle\DoctrineRestDriver\Annotations\Select;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * Tests the annotation reader
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @coversDefaultClass Circle\DoctrineRestDriver\Annotations\Reader
 */
class ReaderTest extends \PHPUnit\Framework\TestCase {

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function setUp() {
        AnnotationRegistry::registerFile(__DIR__ . '/../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Entity.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Table.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Column.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Id.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/GeneratedValue.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/OneToMany.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/ManyToOne.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../Annotations/Insert.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../Annotations/Update.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../Annotations/Select.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../Annotations/Delete.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../Annotations/Fetch.php');
    }

    /**
     * @test
     * @group  unit
     * @covers ::__construct
     * @covers ::read
     */
    public function getRoute() {
        $reader   = new Reader();
        $class    = new \ReflectionClass('Circle\DoctrineRestDriver\Tests\Entity\TestEntity');
        $expected = new Select([
            'value' => 'http://127.0.0.1:3000/app_dev.php/mockapi/products'
        ]);

        $this->assertEquals($expected, $reader->read($class, 'Circle\DoctrineRestDriver\Annotations\Select'));
    }
}

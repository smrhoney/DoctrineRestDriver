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

use Circle\DoctrineRestDriver\Annotations\NamedRoute;
use Circle\DoctrineRestDriver\Annotations\Routing;
use Circle\DoctrineRestDriver\Annotations\RoutingTable;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * Tests the routing table
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @coversDefaultClass Circle\DoctrineRestDriver\Annotations\RoutingTable
 */
class RoutingTableTest extends \PHPUnit\Framework\TestCase {

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

        AnnotationRegistry::registerFile(__DIR__ . '/../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/NamedNativeQueries.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/NamedNativeQuery.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/SqlResultSetMapping.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/SqlResultSetMappings.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/EntityResult.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/FieldResult.php');


        AnnotationRegistry::registerFile(__DIR__ . '/../../Annotations/Insert.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../Annotations/Update.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../Annotations/Select.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../Annotations/Delete.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../Annotations/Fetch.php');

        AnnotationRegistry::registerFile(__DIR__ . '/../../Annotations/NamedRoute.php');
        AnnotationRegistry::registerFile(__DIR__ . '/../../Annotations/NamedRoutes.php');
    }

    /**
     * @test
     * @group  unit
     * @covers ::__construct
     * @covers ::get
     * @covers ::<private>
     */
    public function get() {
        $entities = [
            'categories'     => 'Circle\DoctrineRestDriver\Tests\Entity\AssociatedEntity',
            'nonImplemented' => 'Circle\DoctrineRestDriver\Tests\Entity\NonImplementedEntity',
            'products'       => 'Circle\DoctrineRestDriver\Tests\Entity\TestEntity',
        ];

        $routingTable = new RoutingTable($entities);
        $expected     = new Routing($entities['categories']);

        $this->assertEquals($expected, $routingTable->get('categories'));
    }

    /**
     * @test
     * @group unit
     * @covers ::namedQueries
     */
    public function namedQueries() {
        $entities = [
            'categories'     => 'Circle\DoctrineRestDriver\Tests\Entity\AssociatedEntity',
            'nonImplemented' => 'Circle\DoctrineRestDriver\Tests\Entity\NonImplementedEntity',
            'products'       => 'Circle\DoctrineRestDriver\Tests\Entity\TestEntity',
        ];

        $routingTable = new RoutingTable($entities);
        $namedRoutes = $routingTable->get('products')->namedRoutes();
        $namedNativeQueries = $routingTable->get('products')->namedNativeQueries();

        $expected     = [
            new NamedRoute([
                'name' => 'new-products',
                'value' => 'http://127.0.0.1/app_dev.php/mockapi/new-products'
            ]),
            new NamedRoute([
                'name'  => 'recall-products',
                'value' => 'http://127.0.0.1/app_dev.php/mockapi/recall-products'
            ]),
        ];

        $this->assertCount(2, $namedNativeQueries);
        $this->assertEquals($expected, $namedRoutes);
    }
}

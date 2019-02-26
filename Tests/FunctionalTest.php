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

namespace Circle\DoctrineRestDriver\Tests;

use Circle\DoctrineRestDriver\Driver;
use Circle\DoctrineRestDriver\Tests\Entity\AssociatedEntity;
use Circle\DoctrineRestDriver\Tests\Entity\TestEntity;
use Circle\DoctrineRestDriver\Wrapper;
use Circle\RestClientBundle\Exceptions\Interfaces\DetailedExceptionInterface;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\EventManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\EntityManager;

/**
 * Tests against a mock api
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @SuppressWarnings("PHPMD.TooManyPublicMethods")
 * @SuppressWarnings("PHPMD.StaticAccess")
 */
class FunctionalTest extends WebTestCase {

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * {@inheritdoc}
     */
    public function setUp() {
        static::bootKernel(['debug' => false]);
        $this->em = static::$kernel->getContainer()->get('doctrine.orm.default_entity_manager');
    }

    /**
     * @test
     * @group functional
     * @covers \Circle\DoctrineRestDriver\Wrapper
     */
    public function eventManager(){
        $connection = $this->em->getConnection();
        $this->assertInstanceOf(Wrapper::class, $connection);
        /** @var Driver $driver */
        $driver = $connection->getDriver();
        $this->assertInstanceOf(Driver::class, $driver);
        $evm = $driver->getEventManager();
        $this->assertInstanceOf(EventManager::class, $evm);
    }

    /**
     * @test
     * @group  functional
     * @covers \Circle\DoctrineRestDriver\Driver
     * @covers \Circle\DoctrineRestDriver\Connection
     * @covers \Circle\DoctrineRestDriver\Statement
     * @covers \Circle\DoctrineRestDriver\Statement::<private>
     * @covers \Circle\DoctrineRestDriver\MetaData
     */
    public function find() {
        $entity = $this->em->find('Circle\DoctrineRestDriver\Tests\Entity\TestEntity', 1);
        $this->assertSame(1,         $entity->getId());
        $this->assertSame('MyName',  $entity->getName());
        $this->assertSame('MyValue', $entity->getValue());
    }

    /**
     * @test
     * @group  functional
     * @covers Circle\DoctrineRestDriver\Driver
     * @covers Circle\DoctrineRestDriver\Connection
     * @covers Circle\DoctrineRestDriver\Statement
     * @covers Circle\DoctrineRestDriver\Statement::<private>
     * @covers Circle\DoctrineRestDriver\MetaData
     */
    public function findNonExisting() {
        $result = $this->em->find('Circle\DoctrineRestDriver\Tests\Entity\TestEntity', 2);
        $this->assertEquals(null, $result);
    }

    /**
     * @test
     * @group  functional
     * @covers Circle\DoctrineRestDriver\Driver
     * @covers Circle\DoctrineRestDriver\Connection
     * @covers Circle\DoctrineRestDriver\Statement
     * @covers Circle\DoctrineRestDriver\Statement::<private>
     * @covers Circle\DoctrineRestDriver\MetaData
     */
    public function findOneBy() {
        $entity = $this->em->getRepository('Circle\DoctrineRestDriver\Tests\Entity\TestEntity')->findOneBy(['id' => 1]);
        $this->assertSame(1,          $entity->getId());
        $this->assertSame('MyName',   $entity->getName());
        $this->assertSame('MyValue',  $entity->getValue());
    }

    /**
     * @test
     * @group  functional
     * @covers Circle\DoctrineRestDriver\Driver
     * @covers Circle\DoctrineRestDriver\Connection
     * @covers Circle\DoctrineRestDriver\Statement
     * @covers Circle\DoctrineRestDriver\Statement::<private>
     * @covers Circle\DoctrineRestDriver\MetaData
     */
    public function findBy() {
        $entity = $this->em->getRepository('Circle\DoctrineRestDriver\Tests\Entity\TestEntity')->findBy(['id' => 1]);
        $this->assertTrue(is_array($entity));
        $this->assertSame(1,          $entity[0]->getId());
        $this->assertSame('MyName',   $entity[0]->getName());
        $this->assertSame('MyValue',  $entity[0]->getValue());
    }

    /**
     * @test
     * @group  functional
     * @covers Circle\DoctrineRestDriver\Driver
     * @covers Circle\DoctrineRestDriver\Connection
     * @covers Circle\DoctrineRestDriver\Statement
     * @covers Circle\DoctrineRestDriver\Statement::<private>
     * @covers Circle\DoctrineRestDriver\MetaData
     */
    public function findAll() {
        $entity = $this->em->getRepository('Circle\DoctrineRestDriver\Tests\Entity\TestEntity')->findAll();
        $this->assertTrue(is_array($entity));

        $this->assertSame(1,         $entity[0]->getId());
        $this->assertSame('MyName',  $entity[0]->getName());
        $this->assertSame('MyValue', $entity[0]->getValue());

        $this->assertSame(2,           $entity[1]->getId());
        $this->assertSame('NextName',  $entity[1]->getName());
        $this->assertSame('NextValue', $entity[1]->getValue());
    }

    /**
     * @test
     * @group  functional
     * @covers Circle\DoctrineRestDriver\Driver
     * @covers Circle\DoctrineRestDriver\Connection
     * @covers Circle\DoctrineRestDriver\Statement
     * @covers Circle\DoctrineRestDriver\Statement::<private>
     * @covers Circle\DoctrineRestDriver\MetaData
     */
    public function persistAndFlush() {
        $entity = new TestEntity();
        $entity->setName('MyName');
        $entity->setValue('MyValue');

        $associatedEntity = new AssociatedEntity();
        $entity->addCategory($associatedEntity);

        $this->em->persist($associatedEntity);
        $this->em->persist($entity);
        $this->em->flush();

        $this->assertSame(1,         $entity->getId());
        $this->assertSame('MyName',  $entity->getName());
        $this->assertSame('MyValue', $entity->getValue());
        $this->assertSame(1,         $entity->getCategories()->first()->getId());
    }

    /**
     * @test
     * @group  functional
     * @covers Circle\DoctrineRestDriver\Driver
     * @covers Circle\DoctrineRestDriver\Connection
     * @covers Circle\DoctrineRestDriver\Statement
     * @covers Circle\DoctrineRestDriver\Statement::<private>
     * @covers Circle\DoctrineRestDriver\MetaData
     */
    public function updateAndFlush() {
        $entity = $this->em->find('Circle\DoctrineRestDriver\Tests\Entity\TestEntity', 1);
        $entity->setName('newName');
        $this->em->flush();

        $this->assertSame(1,         $entity->getId());
        $this->assertSame('newName', $entity->getName());
        $this->assertSame('MyValue', $entity->getValue());
    }

    /**
     * @test
     * @group  functional
     * @covers Circle\DoctrineRestDriver\Driver
     * @covers Circle\DoctrineRestDriver\Connection
     * @covers Circle\DoctrineRestDriver\Statement
     * @covers Circle\DoctrineRestDriver\Statement::<private>
     * @covers Circle\DoctrineRestDriver\MetaData
     */
    public function remove() {
        $entity = $this->em->find('Circle\DoctrineRestDriver\Tests\Entity\TestEntity', 1);
        $this->em->remove($entity);
        $this->em->flush();
    }

    /**
     * @test
     * @group  functional
     * @covers Circle\DoctrineRestDriver\Driver
     * @covers Circle\DoctrineRestDriver\Connection
     * @covers Circle\DoctrineRestDriver\Statement
     * @covers Circle\DoctrineRestDriver\Statement::<private>
     * @covers Circle\DoctrineRestDriver\MetaData
     */
    public function dql() {
        $entity = $this->em
            ->createQuery('SELECT p FROM Circle\DoctrineRestDriver\Tests\Entity\TestEntity p WHERE p.id = 1')
            ->getSingleResult();

        $this->assertSame(1,         $entity->getId());
        $this->assertSame('MyName',  $entity->getName());
        $this->assertSame('MyValue', $entity->getValue());
    }

    /**
     * @test
     * @group  functional
     * @covers Circle\DoctrineRestDriver\Driver
     * @covers Circle\DoctrineRestDriver\Connection
     * @covers Circle\DoctrineRestDriver\Statement
     * @covers Circle\DoctrineRestDriver\Statement::<private>
     * @covers Circle\DoctrineRestDriver\MetaData
     */
    public function nativeQuery() {
        $mapping = new ResultSetMapping();
        $mapping->addEntityResult('Circle\DoctrineRestDriver\Tests\Entity\TestEntity', 'products');
        $mapping->addFieldResult('products', 'id', 'id');
        $mapping->addFieldResult('products', 'name', 'name');
        $entity = $this->em->createNativeQuery('SELECT id, name FROM products', $mapping)->getResult();

        $this->assertSame(1,        $entity[0]->getId());
        $this->assertSame('MyName', $entity[0]->getName());
        $this->assertSame(null,     $entity[0]->getValue());

        $this->assertSame(2,          $entity[1]->getId());
        $this->assertSame('NextName', $entity[1]->getName());
        $this->assertSame(null,       $entity[1]->getValue());
    }

    /**
     * @test
     * @group  functional
     * @covers Circle\DoctrineRestDriver\Driver
     * @covers Circle\DoctrineRestDriver\Connection
     * @covers Circle\DoctrineRestDriver\Statement
     * @covers Circle\DoctrineRestDriver\Statement::<private>
     * @covers Circle\DoctrineRestDriver\MetaData
     */
    public function dqlWithOrderBy() {
        $entity = $this->em
            ->createQuery('SELECT p FROM Circle\DoctrineRestDriver\Tests\Entity\TestEntity p ORDER BY p.name DESC')
            ->getResult();

        $this->assertSame(2,           $entity[0]->getId());
        $this->assertSame('NextName',  $entity[0]->getName());
        $this->assertSame('NextValue', $entity[0]->getValue());

        $this->assertSame(1,         $entity[1]->getId());
        $this->assertSame('MyName',  $entity[1]->getName());
        $this->assertSame('MyValue', $entity[1]->getValue());
    }

    /**
     * @test
     * @group  functional
     * @covers Circle\DoctrineRestDriver\Driver
     * @covers Circle\DoctrineRestDriver\Connection
     * @covers Circle\DoctrineRestDriver\Statement
     * @covers Circle\DoctrineRestDriver\Statement::<private>
     * @covers Circle\DoctrineRestDriver\MetaData
     * @throws \Exception
     */
    public function dqlWithObjectParameter() {
        try {
            $entity = $this->em
                ->createQuery('SELECT p FROM Circle\DoctrineRestDriver\Tests\Entity\TestEntity p WHERE p.name = ?1')
                ->setParameter(1, 'NextName')
                ->getResult();
            $this->assertSame(1, count($entity));
        } catch (DetailedExceptionInterface $e) {
            echo "Message: " . $e->getDetailedMessage();

            throw $e;
        }


    }

    /**
     * @test
     * @group  functional
     * @covers Circle\DoctrineRestDriver\Driver
     * @covers Circle\DoctrineRestDriver\Connection
     * @covers Circle\DoctrineRestDriver\Statement
     * @covers Circle\DoctrineRestDriver\Statement::<private>
     * @covers Circle\DoctrineRestDriver\MetaData
     */
    public function nonImplementedEntity() {
        $result = $this->em->find('Circle\DoctrineRestDriver\Tests\Entity\NonImplementedEntity', 1);
        $this->assertEquals(null, $result);
    }

    /**
     * @test
     * @group  functional
     * @covers Circle\DoctrineRestDriver\Driver
     * @covers Circle\DoctrineRestDriver\Connection
     * @covers Circle\DoctrineRestDriver\Statement
     * @covers Circle\DoctrineRestDriver\Statement::<private>
     * @covers Circle\DoctrineRestDriver\MetaData
     */
    public function customIdentifierEntity() {
        $this->em->find('Circle\DoctrineRestDriver\Tests\Entity\CustomIdentifierEntity', 1);
    }
}

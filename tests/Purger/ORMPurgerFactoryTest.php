<?php

declare(strict_types=1);

namespace Doctrine\Bundle\FixturesBundle\Tests\Purger;

use Doctrine\Bundle\FixturesBundle\Purger\ORMPurgerFactory;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ORMPurgerFactoryTest extends TestCase
{
    private ORMPurgerFactory $factory;

    /** @var EntityManagerInterface&MockObject */
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        $this->factory = new ORMPurgerFactory();
        $this->em      = $this->createMock(EntityManagerInterface::class);
    }

    public function testCreateDefault(): void
    {
        $purger = $this->factory->createForEntityManager(null, $this->em);

        self::assertSame(ORMPurger::PURGE_MODE_DELETE, $purger->getPurgeMode());
        self::assertSame([], (function () {
            return $this->excluded;
        })->call($purger));
    }

    public function testCreateWithExclusions(): void
    {
        $purger = $this->factory->createForEntityManager(null, $this->em, ['tableName']);

        self::assertSame(ORMPurger::PURGE_MODE_DELETE, $purger->getPurgeMode());
        self::assertSame(['tableName'], (function () {
            return $this->excluded;
        })->call($purger));
    }

    public function testCreateWithTruncate(): void
    {
        $purger = $this->factory->createForEntityManager(null, $this->em, [], true);

        self::assertSame(ORMPurger::PURGE_MODE_TRUNCATE, $purger->getPurgeMode());
        self::assertSame([], (function () {
            return $this->excluded;
        })->call($purger));
    }
}

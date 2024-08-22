<?php

declare(strict_types=1);

namespace Duyler\Database\Action;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\DataFixtures\Loader;

class FixturesAction
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function __invoke()
    {
        echo "Starting Fixture\n";

        $loader = new Loader();
        $loader->loadFromDirectory('fixtures');

        $executor = new ORMExecutor($this->entityManager, new ORMPurger());
        $executor->execute($loader->getFixtures());
    }
}

<?php

declare(strict_types=1);

namespace Duyler\Database\Action;

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\EntityManagerInterface;
//use Doctrine\ORM\Tools\Console\ConsoleRunner;
//use Doctrine\Migrations\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

class OrmAction
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {}

    public function __invoke()
    {
        echo "Starting Orm\n";

        ConsoleRunner::run(new SingleManagerProvider($this->entityManager));
    }
}

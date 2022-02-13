<?php

declare(strict_types=1);

namespace App\Infrastructure\Console;

use App\Infrastructure\ConfigFacade;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class RefreshConfigCommand extends Command
{
    protected static $defaultName = 'config:refresh';

    public function __construct(
        private ContainerInterface $container,
        ?string $name = null
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->container->get(ConfigFacade::class)->refresh();

        return self::SUCCESS;
    }
}

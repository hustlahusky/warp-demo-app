<?php

declare(strict_types=1);

namespace App\Infrastructure\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Warp\Clock\DateTimeImmutableValue;

final class GreetingCommand extends Command
{
    protected static $defaultName = 'greet';

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $name */
        $name = $input->getArgument('name');

        $hours = (int)DateTimeImmutableValue::from('now')->format('H');

        if (18 < $hours) {
            $template = 'Good evening, %s!';
        } elseif (11 < $hours) {
            $template = 'Good afternoon, %s!';
        } else {
            $template = 'Good morning, %s!';
        }

        $output->writeln(\sprintf($template, $name));

        return self::SUCCESS;
    }
}

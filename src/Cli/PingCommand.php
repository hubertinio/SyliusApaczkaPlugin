<?php

declare(strict_types=1);

namespace Hubertinio\SyliusApaczkaPlugin\Cli;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'sylius:shipping:apaczka:ping',
    description: 'Check your API credentials'
)]
final class PingCommand extends \Hubertinio\SyliusApaczkaPlugin\Cli\ApiCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        parent::execute($input, $output);

        $data = $this->apiClient->service_structure();
        $output->writeln(json_encode($data));

        return Command::SUCCESS;
    }
}
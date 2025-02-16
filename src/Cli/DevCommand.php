<?php

declare(strict_types=1);

namespace Hubertinio\SyliusApaczkaPlugin\Cli;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @example docker compose exec app sh -c "cd tests/Application; bin/console sylius:shipping:apaczka:dev --app-id ... --app-secret ..."
 */
#[AsCommand(
    name: 'sylius:shipping:apaczka:dev',
    description: 'Dev API tests'
)]
final class DevCommand extends ApiCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        parent::execute($input, $output);

        $order = $this->getOrder();
//        $this->getOrderValuation($output, $order);
//        $this->getOrderSend($output, $order);
        $this->getOrders($output);

        return Command::SUCCESS;
    }

    public function getOrderValuation(OutputInterface $output, array $order): void
    {
        $data = $this->apiClient->order_valuation($order);
        $output->writeln($data);
    }

    public function getOrders(OutputInterface $output): void
    {
        $data = $this->apiClient->orders();
        $output->writeln($data);
    }

    public function getOrderSend(OutputInterface $output, array $order): void
    {
        $data = $this->apiClient->order_send($order);
        $output->writeln($data);
    }
}
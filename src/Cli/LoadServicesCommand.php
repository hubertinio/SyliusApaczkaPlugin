<?php

declare(strict_types=1);

namespace Hubertinio\SyliusApaczkaPlugin\Cli;

use Hubertinio\SyliusApaczkaPlugin\Api\ApaczkaApiClient;
use Hubertinio\SyliusApaczkaPlugin\Api\ApaczkaApiClientInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

/**
 * @TODO opcja save dla zapisu do tabeli shipping_method
 * @TODO opcja filter na service_id
 *
 * @example docker compose exec app sh -c "cd tests/Application; bin/console sylius:shipping:apaczka:load-services --app-id ... --app-secret ..."
 */
#[AsCommand(
    name: 'sylius:shipping:apaczka:load-services',
    description: 'Load supported services'
)]
final class LoadServicesCommand extends ApiCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        parent::execute($input, $output);

        $data = $this->apiClient::service_structure();
        $data = json_decode($data, true);

        $table = new Table($output);
        $table->setHeaders(['ID', 'Supplier', 'Method',  'Poland']);

        foreach ($data['response']['services'] ?? [] as $row) {
            $table->addRow([
                $row['service_id'],
                $row['supplier'],
                $row['name'],
                ((int) $row['domestic'])  ?  'x': '' ,
            ]);
        }

        $table->render();

        return Command::SUCCESS;
    }
}
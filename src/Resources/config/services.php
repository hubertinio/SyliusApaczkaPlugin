<?php

declare(strict_types=1);

use Hubertinio\SyliusApaczkaPlugin\Api\ApaczkaApiClient;
use Hubertinio\SyliusApaczkaPlugin\Api\ApaczkaApiClientInterface;
use Hubertinio\SyliusApaczkaPlugin\Api\CachedApaczkaApiClient;
use Hubertinio\SyliusApaczkaPlugin\Calculator\Calculator;
use Hubertinio\SyliusApaczkaPlugin\Calculator\PerApaczkaOrderRateCalculator;
use Hubertinio\SyliusApaczkaPlugin\Cli\ApiCommand;
use Hubertinio\SyliusApaczkaPlugin\Cli\DevCommand;
use Hubertinio\SyliusApaczkaPlugin\Cli\LoadPointsCommand;
use Hubertinio\SyliusApaczkaPlugin\Cli\LoadServicesCommand;
use Hubertinio\SyliusApaczkaPlugin\Cli\PingCommand;
use Hubertinio\SyliusApaczkaPlugin\Form\Type\ShippingConfigurationType;
use Hubertinio\SyliusApaczkaPlugin\Repository\OrderPosRepository;
use Hubertinio\SyliusApaczkaPlugin\Service\Configuration;
use Hubertinio\SyliusApaczkaPlugin\Service\PushService;
use Hubertinio\SyliusApaczkaPlugin\Service\SecurityService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $servicesIdPrefix  = 'hubertinio_sylius_apaczka_plugin.';

    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('Hubertinio\\SyliusApaczkaPlugin\\Controller\\', __DIR__ . '/../../Controller');

    $services->set($servicesIdPrefix . 'configuration', Configuration::class);

    $services->set($servicesIdPrefix . 'api.client', ApaczkaApiClient::class)
        ->factory([service($servicesIdPrefix . 'configuration'), 'clientFactory']);

    $services->set($servicesIdPrefix . 'api.cached_client', CachedApaczkaApiClient::class)
        ->args([
        service($servicesIdPrefix . 'api.client'),
        service('cache.app'),
    ]);

    $services->set($servicesIdPrefix . 'cli.ping', PingCommand::class)
        ->tag('console.command')
        ->args([
        service($servicesIdPrefix . 'api.client'),
    ]);

    $services->set($servicesIdPrefix . 'cli.dev', DevCommand::class)
        ->tag('console.command')
        ->args([
        service($servicesIdPrefix . 'api.client'),
    ]);

    $services->alias(ApaczkaApiClientInterface::class, $servicesIdPrefix . 'api.cached_client');

    $services->set($servicesIdPrefix . 'cli.load_points', LoadPointsCommand::class)
        ->tag('console.command')
        ->args([
            service($servicesIdPrefix . 'api.cached_client'),
        ]
    );

    $services->set($servicesIdPrefix . 'cli.load_services', LoadServicesCommand::class)
        ->tag('console.command')
        ->args([
            service($servicesIdPrefix . 'api.cached_client'),
        ]);

    $services->set($servicesIdPrefix . 'address_shipping_calculator', PerApaczkaOrderRateCalculator::class)
        ->tag('sylius.shipping_calculator', [
            'calculator' => Calculator::PER_ORDER_RATE,
            'form_type' => ShippingConfigurationType::class,
            'label' => 'sylius.form.shipping_calculator.' . Calculator::PER_ORDER_RATE . '_configuration.label',
        ])
    ;

    $services->set($servicesIdPrefix . 'repository.pos', OrderPosRepository::class)
        ->args([
            param('kernel.project_dir'),
            service('filesystem'),
        ]);

    $services
        ->set($servicesIdPrefix . 'form.type.shipping', ShippingConfigurationType::class)
        ->tag('sylius.form.type');

    /**
     * @TODO controller od push ogarnąć
     */
    $services->set($servicesIdPrefix . 'service.push', PushService::class);
    $services->set($servicesIdPrefix . 'service.security', SecurityService::class);
};

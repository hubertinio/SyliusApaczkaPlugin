<?php

declare(strict_types=1);

use Hubertinio\SyliusApaczkaPlugin\Api\ApaczkaApiClient;
use Hubertinio\SyliusApaczkaPlugin\Api\ApaczkaApiClientInterface;
use Hubertinio\SyliusApaczkaPlugin\Api\CachedApaczkaApiClient;
use Hubertinio\SyliusApaczkaPlugin\Calculator\PerApaczkaOrderRateCalculator;
use Hubertinio\SyliusApaczkaPlugin\Cli\DevCommand;
use Hubertinio\SyliusApaczkaPlugin\Cli\LoadPointsCommand;
use Hubertinio\SyliusApaczkaPlugin\Cli\LoadServicesCommand;
use Hubertinio\SyliusApaczkaPlugin\Cli\PingCommand;
use Hubertinio\SyliusApaczkaPlugin\Service\PushService;
use Hubertinio\SyliusApaczkaPlugin\Service\SecurityService;
use Sylius\Bundle\CoreBundle\Form\Type\Shipping\Calculator\ChannelBasedPerUnitRateConfigurationType;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $servicesIdPrefix  = 'hubertinio_sylius_apaczka_plugin.';

    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('Hubertinio\\SyliusApaczkaPlugin\\Controller\\', __DIR__ . '/../src/Controller');

    /**
     * @TODO dane z konfiguracji
     */
    $services->set($servicesIdPrefix . 'api.client', ApaczkaApiClient::class)
        ->arg('$appId', '@todo factory')
        ->arg('$appSecret', '@todo factory');

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
        service($servicesIdPrefix . 'api.cached_client'),
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
        ->tag($servicesIdPrefix . 'shipping_calculator', [
        'calculator' => 'per_apaczka_order_rate',
        'form_type' => ChannelBasedPerUnitRateConfigurationType::class,
        'form-type' => ChannelBasedPerUnitRateConfigurationType::class,
        'label' => 'sylius.form.shipping_calculator.per_unit_rate_configuration.label',
    ]);

    /**
     * @TODO controller od push ogarnąć
     */
    $services->set($servicesIdPrefix . 'service.push', PushService::class);
    $services->set($servicesIdPrefix . 'service.security', SecurityService::class);
};

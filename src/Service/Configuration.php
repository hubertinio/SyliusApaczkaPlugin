<?php

namespace Hubertinio\SyliusApaczkaPlugin\Service;

use Hubertinio\SyliusApaczkaPlugin\Api\ApaczkaApiClient;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class Configuration
{
    public function __construct(
        #[Autowire(service: 'sylius.registry.shipping_calculator')] private ServiceRegistryInterface $calculators,
    ){
    }

    public function clientFactory(): ?object
    {
        return new ApaczkaApiClient();
    }
}
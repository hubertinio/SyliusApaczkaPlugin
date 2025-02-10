<?php

declare(strict_types=1);

namespace Hubertinio\SyliusApaczkaPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class HubertinioSyliusApaczkaPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function boot(): void
    {
        $this->path = \dirname(__FILE__);
    }
}

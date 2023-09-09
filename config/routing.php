<?php

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('hubertinio_sylius_apaczka_push_tracking', '/apaczka/push-tracking')
        ->controller(Hubertinio\SyliusApaczkaPlugin\Controller\PushTrackingController::class . '::index');

//    $routes->add('hubertinio_sylius_apaczka_dynamic_welcome', '/dynamic-welcome/{name}')
//        ->controller(Hubertinio\SyliusApaczkaPlugin\Controller\PushTrackingController::class . '::dynamicallyGreetAction');
};
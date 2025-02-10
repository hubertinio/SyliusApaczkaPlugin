<?php

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes->add('hubertinio_sylius_apaczka_checkout_select_point', '/checkout/select-shipping/apaczka/select-point')
        ->controller(Hubertinio\SyliusApaczkaPlugin\Controller\CheckoutController::class . '::selectPoint')
        ->methods(['POST'])
    ;

    $routes->add('hubertinio_sylius_apaczka_checkout_selected_point', '/checkout/select-shipping/apaczka/selected-point')
        ->controller(Hubertinio\SyliusApaczkaPlugin\Controller\CheckoutController::class . '::selectedPoint')
        ->methods(['GET'])
    ;

//    $routes->add('hubertinio_sylius_apaczka_shop_push_tracking', '/apaczka/push-tracking')
//        ->controller(Hubertinio\SyliusApaczkaPlugin\Controller\PushTrackingController::class . '::index');
};
<?php

declare(strict_types=1);

namespace Hubertinio\SyliusApaczkaPlugin\Controller;

use ECSPrefix202306\Symfony\Component\HttpKernel\Attribute\AsController;
use Hubertinio\SyliusApaczkaPlugin\Repository\OrderPosRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;
use Throwable;

#[AsController]
final class CheckoutController extends AbstractController
{
    public function __construct(
        #[Autowire(service: 'hubertinio_sylius_apaczka_plugin.repository.pos')] private OrderPosRepository $posRepository,
        private LoggerInterface $logger
    ) {
    }

    public function selectPoint(Request $request): JsonResponse
    {
        try {
            $json = $request->getContent();
            $data = json_decode($json, true);

            Assert::keyExists($data, 'orderId');
            Assert::keyExists($data, 'pos');

            $orderId = $data['orderId'];
            $pos = $data['pos'];

            $this->posRepository->save($orderId, $pos);

            $output = json_encode(['status' => 'ok']);

            return JsonResponse::fromJsonString($output);
        } catch (Throwable $e) {
            $this->logger->critical($e->getMessage());

            return JsonResponse::fromJsonString(
                json_encode(['status' => $e->getMessage()]),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

    }
}

<?php

declare(strict_types=1);

namespace Hubertinio\SyliusApaczkaPlugin\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Hubertinio\SyliusApaczkaPlugin\Service\PushService;

final class PushTrackingController extends AbstractController
{
    private PushService $pushService;

    private LoggerInterface $logger;

    public function __construct(PushService $pushService, LoggerInterface $logger)
    {
        $this->pushService = $pushService;
        $this->logger = $logger;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $json = $request->getContent();
            $data = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS);
            $this->pushService->handle(...$data);

            return JsonResponse::fromJsonString('ok');
        } catch (Throwable $e) {
            $this->logger->critical($e->getMessage());

            return JsonResponse::fromJsonString(
                json_encode(['error' => $e->getMessage()]),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

    }
}
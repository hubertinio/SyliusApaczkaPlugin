<?php

declare(strict_types=1);

namespace Hubertinio\SyliusApaczkaPlugin\Service;

use Psr\Log\LoggerInterface;

final class PushService
{
    private SecurityService $securityService;

    private LoggerInterface $logger;

    public function __construct(
        SecurityService $securityService,
        LoggerInterface $logger
    ) {
        $this->securityService = $securityService;
        $this->logger = $logger;
    }

    public function handle(string $signature, array $response): bool
    {
        try {
            if ($this->securityService->verifyPushTracking($signature)) {
                return false;
            }

            /**
             * @TODO sprawdzić czy orderNumber istnieje
             * @TODO zmienić status (event?)
             */

            return true;

        } catch (\Throwable $e) {
            $this->logger->critical($e->getMessage(), [
                'method' => __METHOD__,
                'signature' => $signature,
                'orderNumber' => $orderNumber,
            ]);

            throw $e;
        }

        return false;
    }
}
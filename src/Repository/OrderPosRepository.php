<?php

namespace Hubertinio\SyliusApaczkaPlugin\Repository;

use Symfony\Component\Filesystem\Filesystem;

class OrderPosRepository
{
    public function __construct(
        private string $projectDir,
        private Filesystem $filesystem,
    ) {
    }

    public function save(string $orderId, array $data): void
    {
        $directory = $this->projectDir . '/var';
        $this->filesystem->mkdir($directory);
        $filePath = $directory . '/' . $orderId . '.json';
        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $this->filesystem->dumpFile($filePath, $jsonData);
    }

    public function find(string $orderId): array
    {
        $directory = $this->projectDir . '/var';
        $filePath = $directory . '/' . $orderId . '.json';

        if (!$this->filesystem->exists($filePath)) {
            return [];
        }

        $jsonData = file_get_contents($filePath);
        return json_decode($jsonData, true);
    }
}
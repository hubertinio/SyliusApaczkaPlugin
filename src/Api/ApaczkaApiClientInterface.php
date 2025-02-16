<?php

namespace Hubertinio\SyliusApaczkaPlugin\Api;

interface ApaczkaApiClientInterface
{
    public static function setAppId(string $appId);

    public static function setAppSecret(string $appSecret);
}
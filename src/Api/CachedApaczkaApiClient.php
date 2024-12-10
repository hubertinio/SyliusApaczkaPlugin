<?php

declare(strict_types=1);

namespace Hubertinio\SyliusApaczkaPlugin\Api;

use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @see https://www.apaczka.pl/integracje/
 */
class CachedApaczkaApiClient implements ApaczkaApiClientInterface
{
    private const CACHE_TTL = 86400;

        private const OMMIT_METHODS = [
        'getSignature',
                            'stringToSign'
        ];

    private static ApaczkaApiClientInterface $client;

    private  static  CacheInterface$cache;

    public function __construct(
        ApaczkaApiClientInterface $client,
        CacheInterface $cache,
    ) {
        self::$client = $client;
        self::$cache = $cache;
    }

    public static function __callStatic($name, $arguments)
    {
        if (in_array($name, self::OMMIT_METHODS)) {
            return call_user_func_array([self::$client, $name], $arguments);
        }

            $cacheKey = md5($name . json_encode($arguments));

        return self::$cache->get($cacheKey, function (ItemInterface$item) use ($name, $arguments) {
            $item->expiresAfter(self::CACHE_TTL);

            $output = call_user_func_array([self::$client, $name], $arguments);

                        return$output;
        });
        }
}

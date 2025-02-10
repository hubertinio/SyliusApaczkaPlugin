<?php

declare(strict_types=1);

namespace Hubertinio\SyliusApaczkaPlugin\Api;

use SensitiveParameter;
use Webmozart\Assert\Assert;

/**
 * @see https://www.apaczka.pl/integracje/
 */
class ApaczkaApiClient implements ApaczkaApiClientInterface
{
    public const POINTS_TYPES = [
        "INPOST",
        "UPS",
        "POCZTA",
    ];

    public static ?string $appId = null;
    public static ?string $appSecret = null;

    public function __construct(
        #[SensitiveParameter] ?string $appId = null,
        #[SensitiveParameter] ?string $appSecret = null
    ) {
        self::$appId = $appId;
        self::$appSecret = $appSecret;
    }

    public static function setAppId(string $appId): void
    {
        self::$appId = $appId;
    }

    public static function setAppSecret(string $appSecret): void
    {
        self::$appSecret = $appSecret;
    }

    public function __call($name, $arguments) {
        $sdk = new Sdk();
        $sdk::$APP_ID = self::$appId;
        $sdk::$APP_SECRET = self::$appSecret;

        return $sdk->$name(...$arguments);
    }
}

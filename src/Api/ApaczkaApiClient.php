<?php

declare(strict_types=1);

namespace Hubertinio\SyliusApaczkaPlugin\Api;

use Webmozart\Assert\Assert;

/**
 * @see https://www.apaczka.pl/integracje/
 */
class ApaczkaApiClient implements ApaczkaApiClientInterface
{
    private const API_URL = 'https://www.apaczka.pl/api/v2/';
    private const SIGN_ALGORITHM = 'sha256';
    private const EXPIRES = '+30min';

    public const POINTS_TYPES = [
        "INPOST",
        "UPS",
        "POCZTA",
    ];

    private  static string $appId;
    private static string $appSecret;

    public function __construct(string $appId, string $appSecret)
    {
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

    public static function request( $route, $data = null)
    {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, self::API_URL . $route );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( self::buildRequest($route, $data) ) );

        $result = curl_exec( $ch );

        if ( $result === false )
        {
            curl_close( $ch );
            return false;
        }

        curl_close( $ch );

        return $result;
    }

    public static function buildRequest( $route, $data = [] )
    {
        $data = json_encode($data);
        $expires = strtotime( self::EXPIRES );

        return [
            'app_id'    => static::$appId,
            'request'   => $data,
            'expires'   => $expires,
            'signature' => self::getSignature( self::stringToSign( static::$appId, $route, $data, $expires ), static::$appSecret)
        ];
    }

    /**
     * Fetch order details
     */
    public static function order($id)
    {
        return self::request( __FUNCTION__ . '/' . $id . '/' );
    }

    public static function orders($page = 1, $limit = 10)
    {
        return self::request( __FUNCTION__ . '/', [
            'page' => $page,
            'limit' => $limit
        ]);
    }

    /**
     * Fetch waybill print
     */
    public static function waybill($id)
    {
        return self::request( __FUNCTION__ . '/' . $id . '/' );
    }

    public static function pickup_hours($postal_code, $service_id = false)
    {
        return self::request( __FUNCTION__ . '/', [
            'postal_code' => $postal_code,
            'service_id' => $service_id
        ]);
    }

    public static function order_valuation(array $order)
    {
        $order = json_encode($order);

        return self::request( __FUNCTION__ . '/', [
            'order' => $order
        ]);
    }

    public static function order_send(array $order)
    {
        $order = json_encode($order);

        return self::request( __FUNCTION__ . '/', [
            'order' => $order
        ]);
    }

    public static function cancel_order(string $id )
    {
        return self::request( __FUNCTION__ . '/' . $id . '/' );
    }

    public static function service_structure()
    {
        return self::request( __FUNCTION__ . '/');
    }

    public static function points(string $type)
    {
        $type = mb_strtoupper(trim($type));
        Assert::oneOf($type, self::POINTS_TYPES);

        return self::request( __FUNCTION__ . '/' . $type . '/');
    }

    public static function customer_register(string $customer)
    {
        return self::request( __FUNCTION__ . '/', [
            'customer' => $customer
        ]);
    }

    public static function turn_in(array $order_ids = [])
    {
        return self::request( __FUNCTION__ . '/', [
            'order_ids' => $order_ids
        ]);
    }

    public static function getSignature( string $string, string $key )
    {
        return hash_hmac( self::SIGN_ALGORITHM, $string, $key );
    }

    public static function stringToSign( string $appId, string $route, string $data,  int $expires )
    {
        return sprintf( "%s:%s:%s:%s", $appId, $route, $data, $expires );
    }
}

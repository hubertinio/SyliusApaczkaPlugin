<?php

namespace Hubertinio\SyliusApaczkaPlugin\Api;

/**
 * Original api.class.php
 *
 * @see https://www.apaczka.pl/integracje/
 */
class Sdk {
    public static ?string $APP_ID = null;
    public static ?string $APP_SECRET = null;

	const API_URL = 'https://www.apaczka.pl/api/v2/';

	const SIGN_ALGORITHM = 'sha256';

	const EXPIRES = '+30min';

	public static function request( $route, $data = null) {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, self::API_URL . $route );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_HEADER, false); // Exclude headers from response

		curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( self::buildRequest($route, $data) ) );

		$result = curl_exec( $ch );

		if ( $result === false ) {
			curl_close( $ch );

			return false;
		}
		curl_close( $ch );

		return $result;
	}

	public static function buildRequest( $route, $data = [] ) {
		$data = json_encode($data);
		$expires = strtotime( self::EXPIRES );
		return [
			'app_id'    => self::$APP_ID,
			'request'   => $data,
			'expires'   => $expires,
			'signature' => self::getSignature( self::stringToSign( self::$APP_ID, $route, $data, $expires ), self::$APP_SECRET )
		];
	}

	public static function order( $id ) {
		return self::request( __FUNCTION__ . '/' . $id . '/' );
	}

	public static function orders ($page = 1, $limit = 10) {
		return self::request( __FUNCTION__ . '/', [
			'page' => $page,
			'limit' => $limit
		]);
	}

	public static function waybill( $id ) {
		return self::request( __FUNCTION__ . '/' . $id . '/' );
	}

	public static function pickup_hours ($postal_code, $service_id = false) {
		return self::request( __FUNCTION__ . '/', [
			'postal_code' => $postal_code,
			'service_id' => $service_id
		]);
	}

	public static function order_valuation ($order) {
		return self::request( __FUNCTION__ . '/', [
			'order' => $order
		]);
	}

	public static function order_send ($order) {
		return self::request( __FUNCTION__ . '/', [
			'order' => $order
		]);
	}

	public static function cancel_order( $id ) {
		return self::request( __FUNCTION__ . '/' . $id . '/' );
	}

	public static function service_structure () {
		return self::request( __FUNCTION__ . '/');
	}

	public static function points ($type = null) {
		return self::request( __FUNCTION__ . '/' . $type . '/');
	}

	public static function customer_register ($customer) {
		return self::request( __FUNCTION__ . '/', [
			'customer' => $customer
		]);
	}

	public static function turn_in( $order_ids = [] ) {
		return self::request( __FUNCTION__ . '/', [
			'order_ids' => $order_ids
		]);
	}

	/**
	 * @param $string
	 * @param $key
	 *
	 * @return string
	 */
	public static function getSignature( $string, $key ) {
		return hash_hmac( self::SIGN_ALGORITHM, $string, $key );
	}

	/**
	 * @param $appId
	 * @param $route
	 * @param $data
	 * @param $expires
	 *
	 * @return string
	 */
	public static function stringToSign( $appId, $route, $data, $expires ) {
		return sprintf( "%s:%s:%s:%s", $appId, $route, $data, $expires );
	}
}

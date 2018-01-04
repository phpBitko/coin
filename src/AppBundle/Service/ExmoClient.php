<?php

/**
 * Bittrex API wrapper class
 *
 * @author Edson Medina <edsonmedina@gmail.com>
 */

namespace AppBundle\Service;

class ExmoClient
{
	private $baseUrl;
	private $apiKey;
	private $apiSecret;

	public function __construct($apiKey, $apiSecret) {
		$this->apiKey = $apiKey;
		$this->apiSecret = $apiSecret;
		$this->baseUrl = 'http://api.exmo.com/v1/';
	}


	public function api_query($api_name, array $req = array()) {
		$mt = explode(' ', microtime());
		$NONCE = $mt[1].substr($mt[0], 2, 6);

		// API settings

		$url = $this->baseUrl.$api_name;

		$req['nonce'] = $NONCE;

		// generate the POST data string
		$post_data = http_build_query($req, '', '&');

		$sign = hash_hmac('sha512', $post_data, $this->apiSecret);

		// generate the extra headers
		$headers = array(
			'Sign: '.$sign,
			'Key: '.$this->apiKey,
		);

		// our curl handle (initialize if required)
		static $ch = null;
		if (is_null($ch)) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; PHP client; '.php_uname('s').'; PHP/'
				.phpversion().')');
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		// run the query
		$res = curl_exec($ch);
		dump($res);
		if ($res === false) {
			throw new \Exception('Could not get reply: '.curl_error($ch));
		}

		$dec = json_decode($res, true);
		if ($dec === null) {
			throw new \Exception('Invalid data received, please make sure connection is working and requested API exists');
		}

		return $dec;
	}

	public function getUserInfo(){
		return $this->api_query('user_info');
	}

	public function tradeHistory(array $parameter = array()){
		return $this->api_query('user_trades', $parameter);
	}
}

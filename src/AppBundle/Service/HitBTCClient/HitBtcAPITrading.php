<?php

namespace AppBundle\Service\HitBTCClient;

use AppBundle\Service\HitBTCClient\tools\Request;

/**
 * HitBTC Trading API Methods.
 *
 * Please note that making more than 6 calls per second to the public API, or
 * repeatedly and needlessly fetching excessive amounts of data, can result in
 * your IP being banned.
 *
 * @link URL https://hitbtc.com/api
 *
 * @category HitBTC API
 * @author Dmytro Zarezenko <dmytro.zarezenko@gmail.com>
 * @copyright (c) 2017, Dmytro Zarezenko
 *
 * @git https://github.com/dzarezenko/hitbtc-api
 * @license http://opensource.org/licenses/MIT
 */
class HitBtcAPITrading {

    private $apiKey;
    private $apiSecret;

    private $request = null;

    /**
     * Constructor of the class.
     *
     * @param string $apiKey HitBTC API key
     * @param string $apiSecret HitBTC API secret
     * @param bool $isDemoAPI Demo API flag
     */
    public function __construct($apiKey, $apiSecret, $isDemoAPI = false) {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->request = new Request($this->apiKey, $this->apiSecret, $this->apiVersion, $isDemoAPI);
    }

    /**
     * Returns all of your available balances.
     *
     * @param bool $hideZeroBalances Hide zero balances or not.
     *
     * @return array JSON data.
     */
    public function getBalances($hideZeroBalances = false) {
        switch ($this->apiVersion) {
            case 1:
                $balances = $this->_request('balance');
                if ($hideZeroBalances) {
                    return array_filter($balances, function ($e) {
                        return ($e['cash'] != 0 || $e['reserved'] != 0);
                    });
                }
                break;
            case 2:
                $balances = $this->_request('balance', "trading/balance");
                if ($hideZeroBalances) {
                    return array_filter($balances, function ($e) {
                        return ($e['available'] != 0 || $e['reserved'] != 0);
                    });
                }
                break;
        }

        return $balances;
    }

    /**
     * Returns all orders in status new or partiallyFilled.
     *
     * @param string $clientOrderId Unique order ID.
     *
     * @return array JSON data.
     */
    public function getActiveOrders($clientOrderId = null) {
        $params = [];
        if ($clientOrderId) {
            $params['clientOrderId'] = $clientOrderId;
        }

        switch ($this->apiVersion) {
            case 1:
                return $this->_request('orders', "orders/active", $params);
            case 2:
                return $this->_request('order', $clientOrderId ? "order/{$clientOrderId}" : null);
        }
    }

	public function tradeHistory() {
		switch ($this->apiVersion) {
		case 2:
			return $this->_request('Trades history',  "history/trades");
			break;
		}

		return $this->_request('Trades history');
	}

    /**
     * JSON request functionality wrapper.
     *
     * @param string $method API method name
     * @param string $request API request
     *
     * @return array JSON data.
     */
    private function _request($method, $request = null, $params = []) {
        if (is_null($request)) {
            $request = $method;
        }

        $response = $this->request->exec($request, $params);

        if (isset($response[$method])) {
            return $response[$method];
        }

        return $response;
    }

}

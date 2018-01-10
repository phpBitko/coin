<?php

namespace AppBundle\Service;

use AppBundle\Entity\CryptoCurrency;
use AppBundle\Entity\Balances;
use AppBundle\Entity\OrderHistory;
use AppBundle\Repository\cryptoCurrencyRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Acl\Exception\Exception;

Class Currency
{
	/**
	 * @var string
	 */
	protected $errors;
	protected $entityManager;

	public function __construct(EntityManagerInterface $entityManager) {
		$this->entityManager = $entityManager;
	}

	public function apiArrayToObject(array $data, CryptoCurrency $cryptoCurrency = null) {
		try {
			if ($cryptoCurrency === null) {
				$cryptoCurrency = new CryptoCurrency();
			}
			$cryptoCurrency->setName($data['name']);
			$cryptoCurrency->setSymbol($data['symbol']);
			$cryptoCurrency->setRank($data['rank']);

			if (!empty($data['price_usd'])) {
				$cryptoCurrency->setPriceUsd($data['price_usd']);
			}
			if (!empty($data['price_btc'])) {
				$cryptoCurrency->setPriceBtc($data['price_btc']);
			}
			if (!empty($data['24h_volume_usd'])) {
				$cryptoCurrency->setVolumeUsd24h($data['24h_volume_usd']);
			}
			if (!empty($data['market_cap_usd'])) {
				$cryptoCurrency->setMarketCapUsd($data['market_cap_usd']);
			}

			if (!empty($data['available_supply'])) {
				$cryptoCurrency->setAvailableSupply($data['available_supply']);
			}

			if (!empty($data['total_supply'])) {
				$cryptoCurrency->setTotalSupply($data['total_supply']);
			}

			if (!empty($data['max_supply'])) {
				$cryptoCurrency->setMaxSupply($data['max_supply']);
			}
			if (!empty($data['percent_change_1h'])) {
				$cryptoCurrency->setPercentChange1h($data['percent_change_1h']);
			}
			if (!empty($data['percent_change_7d'])) {
				$cryptoCurrency->setPercentChange7d($data['percent_change_7d']);
			}
			if (!empty($data['percent_change_24h'])) {
				$cryptoCurrency->setPercentChange24h($data['percent_change_24h']);
			}
			if (!empty($data['id'])) {
				$cryptoCurrency->setIdApi($data['id']);
			}
			if (!empty($data['last_updated'])) {
				$lastUpdated = date('Y-m-d H:i:s', $data['last_updated']);
				$lastUpdated = new \DateTime($lastUpdated);
				$lastUpdated->setTimezone(new \DateTimeZone('Europe/Kiev'));
				$cryptoCurrency->setLastUpdated($lastUpdated);
			}

			return $cryptoCurrency;

		} catch (\Exception $exception) {
			$this->errors = $exception->getMessage();
			return false;
		}
	}

	public function apiBittrexToObjectBalances($data) {
		try {
			$balances = new Balances();
			if (!empty($data['Currency'])) {
				$balances->setCurrency($data['Currency']);
			}
			if (!empty($data['Balance'])) {
				$balances->setBalance($data['Balance']);
			}
			if (!empty($data['Available'])) {
				$balances->setAvailable($data['Available']);
			}
			if (!empty($data['Pending'])) {
				$balances->setPending($data['Pending']);
			}
			if (!empty($data['CryptoAddress'])) {
				$balances->setCryptoAddress($data['CryptoAddress']);
			}
			if (!empty($data['Requested'])) {
				$balances->setRequested($data['Requested']);
			}
			if (!empty($data['Uuid'])) {
				$balances->setUuid($data['Uuid']);
			}
			$balances->setIsActive(true);
			$balances->setStockExchange('Bittrex');
			$balances = $this->addPrice($balances);

			return $balances;
		} catch (\Exception $exception) {
			$this->errors = $exception->getMessage();
			return false;
		}
	}


	public function apiBittrexToObjectOrderHistory($data) {
		try {
			$em = $this->entityManager;
			$orderHistoryRep = $em->getRepository('AppBundle:OrderHistory');
			$res = $orderHistoryRep->findOneBy(array('orderUuid' => $data['OrderUuid'], 'stockExchange'=>'Bittrex'));
			if(!empty($res)){
				return null;
			}
			$orderHistory = new OrderHistory();

			if (!empty($data['OrderUuid'])) {
				$orderHistory->setOrderUuid($data['OrderUuid']);
			}
			if (!empty($data['Exchange'])) {
				$orderHistory->setExchange($data['Exchange']);
			}
			if (!empty($data['TimeStamp'])) {
				$addDate = new \DateTime($data['TimeStamp']);
				$orderHistory->setAddDate($addDate);
			}
			if (!empty($data['OrderType'])) {
				if($data['OrderType'] == 'LIMIT_BUY'){
					$orderHistory->setOrderType('buy');
				}else if($data['OrderType'] == 'LIMIT_SELL'){
					$orderHistory->setOrderType('sell');
				}else{
					$orderHistory->setOrderType($data['OrderType']);
				}
			}
			if (!empty($data['Limit'])) {
				$orderHistory->setLimit($data['Limit']);
			}
			if (!empty($data['Quantity'])) {
				$orderHistory->setQuantity($data['Quantity']);
			}
			if (!empty($data['QuantityRemaining'])) {
				$orderHistory->setQuantityRemaining($data['QuantityRemaining']);
			}
			if (!empty($data['Commission'])) {
				$orderHistory->setCommission($data['Commission']);
			}
			if (!empty($data['Price'])) {
				$orderHistory->setPrice($data['Price']);
			}
			if (!empty($data['PricePerUnit'])) {
				$orderHistory->setPricePerUnit($data['PricePerUnit']);
			}
			if (!empty($data['IsConditional'])) {
				$orderHistory->setIsConditional($data['IsConditional']);
			}
			if (!empty($data['Condition'])) {
				$orderHistory->setCondition($data['Condition']);
			}
			if (!empty($data['ConditionTarget'])) {
				$orderHistory->setConditionTarget($data['ConditionTarget']);
			}

			if (!empty($data['ImmediateOrCancel'])) {
				$orderHistory->setImmediateOrCancel($data['ImmediateOrCancel']);
			}

			if (!empty($data['Closed'])) {
				$closedDate = new \DateTime($data['Closed']);
				$orderHistory->setClosedDate($closedDate);
			}

			$currency = explode('-', $data['Exchange']);
			$currency = $currency[1];
			$cryptoCurrency = $em->getRepository('AppBundle:CryptoCurrency');
			$cryptoCurrency = $cryptoCurrency->findOneBy(array('symbol' => $currency));
			$orderHistory->setPriceUsdPerUnit($cryptoCurrency->getPriceUsd());
			$orderHistory->setPriceUsd($cryptoCurrency->getPriceUsd()*$orderHistory->getQuantity());

			//$balances->setIsActive(true);
			$orderHistory->setStockExchange('Bittrex');
			//$balances = $this->addPrice($balances);

			return $orderHistory;
		} catch (\Exception $exception) {
			$this->errors = $exception->getMessage();
			return false;
		}
	}

	public function apiLiquiToObjectOrderHistory($data) {
		try {
			$em = $this->entityManager;
			$orderHistoryRep = $em->getRepository('AppBundle:OrderHistory');
			$res = $orderHistoryRep->findOneBy(array('tradeId' => $data['trade_id'], 'stockExchange'=>'Liqui'));
			if(!empty($res)){
				return null;
			}
			$orderHistory = new OrderHistory();

			if (!empty($data['trade_id'])) {
				$orderHistory->setTradeId($data['trade_id']);
			}
			if (!empty($data['pair'])) {
				$pairsValue = explode('_', $data['pair']);
				$pairsValue = $pairsValue[1].'-'.$pairsValue[0];
				$orderHistory->setExchange(strtoupper($pairsValue));
			}
			if (!empty($data['type'])) {
				if($data['type'] == 'Buy'){
					$orderHistory->setOrderType('buy');
				}else if($data['type'] == 'Sell'){
					$orderHistory->setOrderType('sell');
				}else{
					$orderHistory->setOrderType($data['type']);
				}
			}
			if (!empty($data['amount'])) {
				$orderHistory->setQuantity($data['amount']);
			}
			if (!empty($data['rate'])) {
				$orderHistory->setPrice($data['rate']);
				$orderHistory->setPricePerUnit($data['rate']/$data['amount']);
			}

			if (!empty($data['timestamp'])) {
				$addDate = new \DateTime();
				$addDate = $addDate->setTimestamp($data['timestamp']);
				$orderHistory->setAddDate($addDate);
			}
			$currency = explode('-', $orderHistory->getExchange());
			$currency = $currency[1];
			$cryptoCurrency = $em->getRepository('AppBundle:CryptoCurrency');
			$cryptoCurrency = $cryptoCurrency->findOneBy(array('symbol' => $currency));
			if(!empty($cryptoCurrency)){
				$orderHistory->setPriceUsdPerUnit($cryptoCurrency->getPriceUsd());
			}
			$orderHistory->setPriceUsd($cryptoCurrency->getPriceUsd()*$orderHistory->getQuantity());
			$orderHistory->setStockExchange('Liqui');

			return $orderHistory;
		} catch (\Exception $exception) {
			$this->errors = $exception->getMessage();
			return false;
		}
	}

	public function apiHitBTCToObjectOrderHistory($data) {
		try {
			$em = $this->entityManager;
			$orderHistoryRep = $em->getRepository('AppBundle:OrderHistory');
			$res = $orderHistoryRep->findOneBy(array('tradeId' => $data['id'], 'stockExchange'=>'HitBTC'));
			if(!empty($res)){
				return null;
			}
			$orderHistory = new OrderHistory();

			if (!empty($data['id'])) {
				$orderHistory->setTradeId($data['id']);
			}
			if (!empty($data['symbol'])) {
				$subSymbolOne = substr($data['symbol'], -3);

				if($subSymbolOne  == 'SDT'){
					$subSymbolOne = substr($data['symbol'], -4);
					$subSymbolTwo = substr($data['symbol'], 0,-4);
				}else{
					$subSymbolTwo = substr($data['symbol'], 0,-3);
				}
				$pairsValue = $subSymbolOne.'-'.$subSymbolTwo;
				$orderHistory->setExchange(strtoupper($pairsValue));
			}

			if (!empty($data['side'])) {
				if($data['side'] == 'buy'){
					$orderHistory->setOrderType('buy');
				}else if($data['side'] == 'sell'){
					$orderHistory->setOrderType('sell');
				}else{
					$orderHistory->setOrderType($data['side']);
				}
			}
			if (!empty($data['quantity'])) {
				$orderHistory->setQuantity($data['quantity']);
			}
			if (!empty($data['price'])) {
				$orderHistory->setPricePerUnit($data['price']);
				$orderHistory->setPrice($data['price']*$data['quantity']);
			}
			if (!empty($data['fee'])) {
				$orderHistory->setCommission($data['fee']);
			}
			if (!empty($data['timestamp'])) {
				$addDate = new \DateTime($data['timestamp']);
				$orderHistory->setAddDate($addDate);
			}

			$cryptoCurrency = $em->getRepository('AppBundle:CryptoCurrency');
			$cryptoCurrency = $cryptoCurrency->findOneBy(array('symbol' => $subSymbolTwo));
			if(!empty($cryptoCurrency)){
				$orderHistory->setPriceUsdPerUnit($cryptoCurrency->getPriceUsd());
				$orderHistory->setPriceUsd($cryptoCurrency->getPriceUsd()*$orderHistory->getQuantity());
			}

			$orderHistory->setStockExchange('HitBTC');

			return $orderHistory;
		} catch (\Exception $exception) {
			$this->errors = $exception->getMessage();
			return false;
		}
	}

	public function apiCryptopiaToObjectOrderHistory($data) {
		try {
			$em = $this->entityManager;
			$orderHistoryRep = $em->getRepository('AppBundle:OrderHistory');
			$res = $orderHistoryRep->findOneBy(array('tradeId' => $data['TradeId'], 'stockExchange'=>'Cryptopia'));
			if(!empty($res)){
				return null;
			}

			$orderHistory = new OrderHistory();

			if (!empty($data['TradeId'])) {
				$orderHistory->setTradeId($data['TradeId']);
			}
			if (!empty($data['Market'])) {
				$pairsValue = explode('/', $data['Market']);
				$pairsValue = $pairsValue[1].'-'.$pairsValue[0];
				$orderHistory->setExchange(strtoupper($pairsValue));
			}

			if (!empty($data['Type'])) {
				if($data['Type'] == 'Buy'){
					$orderHistory->setOrderType('buy');
				}else if($data['Type'] == 'Sell'){
					$orderHistory->setOrderType('sell');
				}else{
					$orderHistory->setOrderType($data['Type']);
				}
			}

			if (!empty($data['Amount'])) {
				$orderHistory->setQuantity($data['Amount']);
			}
			if (!empty($data['Rate'])) {
				$orderHistory->setPricePerUnit($data['Rate']);
			}
			if (!empty($data['Total'])) {
				$orderHistory->setPrice($data['Total']);
			}
			if (!empty($data['Fee'])) {
				$orderHistory->setCommission($data['Fee']);
			}

			if (!empty($data['TimeStamp'])) {
				$addDate = new \DateTime($data['TimeStamp']);
				$orderHistory->setAddDate($addDate);
			}

			$currency = explode('-', $orderHistory->getExchange());
			$currency = $currency[1];
			$cryptoCurrency = $em->getRepository('AppBundle:CryptoCurrency');
			$cryptoCurrency = $cryptoCurrency->findOneBy(array('symbol' => $currency));
			if(!empty($cryptoCurrency)){
				$orderHistory->setPriceUsdPerUnit($cryptoCurrency->getPriceUsd());
			}
			$orderHistory->setPriceUsd($cryptoCurrency->getPriceUsd()*$orderHistory->getQuantity());
			$orderHistory->setStockExchange('Cryptopia');

			return $orderHistory;
		} catch (\Exception $exception) {
			$this->errors = $exception->getMessage();
			return false;
		}
	}

	public function apiCryptopiaToObjectBalances($data) {
		try {
			$balances = new Balances();
			if (!empty($data['Symbol'])) {
				$balances->setCurrency($data['Symbol']);
			}
			if (!empty($data['Total'])) {
				$balances->setBalance($data['Total']);
			}
			if (!empty($data['Available'])) {
				$balances->setAvailable($data['Available']);
			}
			if (!empty($data['Unconfirmed'])) {
				$balances->setPending($data['Unconfirmed']);
			}
			if (!empty($data['BaseAddress'])) {
				$balances->setCryptoAddress($data['BaseAddress']);
			}
			$balances->setIsActive(true);
			$balances->setStockExchange('Cryptopia');
			$balances = $this->addPrice($balances);

			return $balances;
		} catch (\Exception $exception) {
			$this->errors = $exception->getMessage();

			return false;
		}
	}

	public function apiHitBTCToObjectBalances($data) {
		try {
			$balances = new Balances();
			if (!empty($data['currency'])) {
				$balances->setCurrency($data['currency']);
			}
			if (!empty($data['available'])) {
				$balances->setAvailable($data['available']);
			}

			if (!empty($data['reserved'])) {
				$balances->setPending($data['reserved']);
			}
			$balances->setBalance($balances->getPending() + $balances->getAvailable());
			$balances->setIsActive(true);
			$balances->setStockExchange('HitBTC');
			$balances = $this->addPrice($balances);

			return $balances;
		} catch (\Exception $exception) {
			$this->errors = $exception->getMessage();

			return false;
		}
	}


	public function addPrice(Balances $balances) {
		$em = $this->entityManager;
		$cryptoCurrency = $em->getRepository('AppBundle:CryptoCurrency');
		if($balances->getCurrency() == 'CAT'){
			$cryptoCurrency = $cryptoCurrency->findOneBy(array('name' => 'Catcoin'));
		}else{
			$cryptoCurrency = $cryptoCurrency->findOneBy(array('symbol' => $balances->getCurrency()));
		}

		if ($cryptoCurrency !== null) {
			if($balances->isMyBalance() === true){
				$balances->setProfit($cryptoCurrency->getPriceUsd() * $balances->getBalance()-$balances->getPriceUsd());
			}
			$balances->setPriceUsd($cryptoCurrency->getPriceUsd() * $balances->getBalance());
			$balances->setPriceBtc($cryptoCurrency->getPriceBtc() * $balances->getBalance());
			$balances->setName($cryptoCurrency->getName());
		}

		return $balances;
	}

	/**
	 * @return string
	 */
	public function getErrors() {
		return $this->errors;
	}

}
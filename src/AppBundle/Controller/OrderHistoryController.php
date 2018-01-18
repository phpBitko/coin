<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Statistic;
use AppBundle\Service\CryptopiaClient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Service\BittrexClient;
use AppBundle\Service\PolonexClient;
use AppBundle\Service\Currency;
use AppBundle\Service\HitBTCClient\HitBTC as HitBTCOld;
use AppBundle\Service\LiquiClient\Liqui as LiquiOld;
use AppBundle\Service\YobitClient;
use AppBundle\Entity\OrderStatistic;
use ccxt\kucoin;
use ccxt\bittrex;
use ccxt\yobit;
use ccxt\exmo;
use ccxt\hitbtc2;
use ccxt\liqui;
use ccxt\Exchange;
use ccxt\cryptopia;

use AppBundle\Service\ExmoClient;
use Symfony\Component\Security\Acl\Exception\Exception;

/**
 *
 * @Route("/orderHistory")
 *
 */
class OrderHistoryController extends Controller
{
	protected $isErrors = false;
	protected $errors = array();

	/**
	 * @return Response $response
	 *
	 * @Route("/updateOrderHistory", name="update_order_history",  options={"expose"=true})
	 * @Method("POST")
	 *
	 */
	public function updateAction() {
		try {
			dump('111');
			$em = $this->getDoctrine()->getManager();
			$stockExchange = $em->getRepository('AppBundle:StockExchange')->findBy(['isActive' => true]);
			foreach ($stockExchange as $exchange){
				$apiKey = $this->getParameter('app_bundle.'.lcfirst($exchange->getName()).'_api_key');
				$apiSecret = $this->getParameter('app_bundle.'.lcfirst($exchange->getName()).'_secret_key');
				$exchangeClass = null;
				switch (lcfirst($exchange->getName())){
				case 'bittrex':
					$exchangeClass = new bittrex(array('apiKey'=>$apiKey, 'secret'=>$apiSecret));
					break;
				}

				if(!empty($exchangeClass)){
					$orderHistory = $exchangeClass->fetch_orders();
					$updateOrderHistory = $this->updateOrderHistory($orderHistory, $exchange->getName());
					if ($updateOrderHistory === false) {
						throw new Exception(implode('', $this->errors));
					}
				}
			}
			dump('sss');
			$updateOrderHistoryLiqui = $this->updateOrderHistoryLiqui();
			if ($updateOrderHistoryLiqui === false) {
				$this->isErrors = true;
			}

			$updateOrderHistoryCryptopia = $this->updateOrderHistoryCryptopia();
			if ($updateOrderHistoryCryptopia === false) {
				$this->isErrors = true;
			}

			$updateOrderHistoryHitBTC = $this->updateOrderHistoryHitBTC();
			if ($updateOrderHistoryHitBTC === false) {
				$this->isErrors = true;
			}
			$em->flush();

			$updateOrderStatistic = $this->updateOrderStatistic();
			if ($updateOrderStatistic === false) {
				$this->isErrors = true;
			}


			if ($this->isErrors === true) {
				$message = implode('', $this->errors);
				return new JsonResponse(array('message' => $message), Response::HTTP_BAD_REQUEST);
			} else {
				$message = 'Дані успішно оновлені!';
				return new JsonResponse(array('message' => $message), Response::HTTP_OK);
			}
		} catch (\Exception $exception) {
			return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
		}
	}

	private function updateOrderHistory(array $orderHistory, $stockExchange) {
		try {
			$currency = $this->get('app.service.currency');
			$em = $this->getDoctrine()->getManager();

			foreach ($orderHistory as $k => $v) {
				$orderHistory = $currency->apiToObjectOrderHistory($v, $stockExchange);
				if ($orderHistory === false) {
					$this->errors[] = $currency->getErrors();
					return false;
				}

				if (!empty($orderHistory)) {
					$em->persist($orderHistory);
				}
			}
		} catch (Exception $exception) {
			$this->errors[] = $exception->getMessage();
			return false;
		}
	}

	private function updateOrderHystoryBittrex() {
		$apiKey = $this->getParameter('app_bundle.bittrex_api_key');
		$apiSecret = $this->getParameter('app_bundle.bittrex_secret_key');
		$bittrexClient = new BittrexClient($apiKey, $apiSecret);
		$orderHistoryBittrex = $bittrexClient->getOrderHistory();
		$currency = $this->get('app.service.currency');

		if (empty($orderHistoryBittrex)) {
			$this->errors[] = 'Історію ордерів на Bittrex не знайдено!';
			return false;
		} else {
			$em = $this->getDoctrine()->getManager();
			foreach ($orderHistoryBittrex as $k => $v) {
				$orderHistory = $currency->apiBittrexToObjectOrderHistory($v);
				if ($orderHistory === false) {
					$this->errors[] = $currency->getErrors();

					return false;
				}
				if (!empty($orderHistory)) {
					$em->persist($orderHistory);

				}
			}
			$em->flush();
		}

		return true;
	}


	private function updateOrderHistoryLiqui() {
		$apiKey = $this->getParameter('app_bundle.liqui_api_key');
		$apiSecret = $this->getParameter('app_bundle.liqui_secret_key');
		$liquiClient = new LiquiOld($apiKey, $apiSecret);
		$orderHistoryLiqui = $liquiClient->tradeHistory();
		$currency = $this->get('app.service.currency');
		if (isset($orderHistoryLiqui['success']) && $orderHistoryLiqui['success'] != 1) {
			$this->errors[] = 'Історію ордерів на Liqui не знайдено!';
			return false;
		} else {
			$em = $this->getDoctrine()->getManager();
			foreach ($orderHistoryLiqui['return'] as $k => $v) {
				$orderHistory = $currency->apiLiquiToObjectOrderHistory($v);
				if ($orderHistory === false) {
					$this->errors[] = $currency->getErrors();
					return false;
				}
				if (!empty($orderHistory)) {
					$em->persist($orderHistory);

				}
			}
			$em->flush();
		}
		return true;
	}

	private function updateOrderHistoryHitBTC() {
		$apiKey = $this->getParameter('app_bundle.hitbtc_api_key');
		$apiSecret = $this->getParameter('app_bundle.hitbtc_secret_key');
		$hitBTCClient = new HitBTCOld($apiKey, $apiSecret);
		$orderHistoryHitBtc = $hitBTCClient->tradeHistory();
		$currency = $this->get('app.service.currency');
		if (empty($orderHistoryHitBtc)) {
			$this->errors[] = 'Історію ордерів на HitBtc не знайдено!';
			return false;
		} else {
			$em = $this->getDoctrine()->getManager();
			foreach ($orderHistoryHitBtc as $k => $v) {
				$orderHistory = $currency->apiHitBTCToObjectOrderHistory($v);
				if ($orderHistory === false) {
					$this->errors[] = $currency->getErrors();
					return false;
				}
				if (!empty($orderHistory)) {
					$em->persist($orderHistory);

				}
			}
			$em->flush();
		}

		return true;
	}

	private function updateOrderHistoryExmo() {
		$exmoClient = new ExmoClient();
		$orderHistoryExmo = $exmoClient->tradeHistory();
		return true;
		$currency = $this->get('app.service.currency');
		if (empty($orderHistoryHitBtc)) {
			$this->errors[] = 'Історію ордерів на HitBtc не знайдено!';
			return false;
		} else {
			$em = $this->getDoctrine()->getManager();
			foreach ($orderHistoryHitBtc as $k => $v) {
				$orderHistory = $currency->apiHitBTCToObjectOrderHistory($v);
				if ($orderHistory === false) {
					$this->errors[] = $currency->getErrors();
					return false;
				}
				if (!empty($orderHistory)) {
					$em->persist($orderHistory);
				}
			}
			$em->flush();
		}

		return true;
	}

	private function updateOrderHistoryCryptopia() {
		$apiKey = $this->getParameter('app_bundle.cryptopia_api_key');
		$apiSecret = $this->getParameter('app_bundle.cryptopia_secret_key');
		$cryptopiaClient = new CryptopiaClient($apiKey, $apiSecret);
		$orderHistoryCriptopia = $cryptopiaClient->getTradeHistory(array('market'=>''));
		if(isset($orderHistoryCriptopia['success'])){
			if($orderHistoryCriptopia['success'] == true){
				$currency = $this->get('app.service.currency');
				$em = $this->getDoctrine()->getManager();
				foreach ($orderHistoryCriptopia['result'] as $k => $v) {
					$orderHistory = $currency->apiCryptopiaToObjectOrderHistory($v);
					if ($orderHistory === false) {
						$this->errors[] = $currency->getErrors();
						return false;
					}
					if (!empty($orderHistory)) {
						$em->persist($orderHistory);
					}
				}
				$em->flush();

			}else{
				$this->errors[]= $orderHistoryCriptopia['message'];
				return false;
			}
		}else{
			$this->errors[]= 'Помилка під час отримання даних із Cryptopia!';
			return false;
		}
		return true;
	}

	public function updateOrderStatistic() {
		try {
			$em = $this->getDoctrine()->getManager();
			$em->getRepository('AppBundle:OrderStatistic')->setNoActive();
			$arrayOrderStatistic = $em->getRepository('AppBundle:OrderHistory')->getOrderStatistic();
			if (!empty($arrayOrderStatistic)){
				foreach ($arrayOrderStatistic as $orderStatisticOne){
					$orderStatistic = new OrderStatistic();
					foreach ($orderStatisticOne as $k => $v) {
						$reflectionMethod = new \ReflectionMethod('AppBundle\Entity\OrderStatistic', 'set'.ucfirst($k));
						$reflectionMethod->invoke($orderStatistic, $v);
					}
					$orderStatistic->setLeftCount($orderStatistic->getBuyCount() - $orderStatistic->getSellCount());
					$orderStatistic->setIsActive(true);
					if($orderStatistic->getLeftCount()>0){
						$currency = explode('-', $orderStatistic->getExchange());
						if(isset($currency[1])){
							$currency = $currency[1];
							$cryptoCurrency = $em->getRepository('AppBundle:CryptoCurrency')->findOneBy(array('symbol'=>$currency));
							$orderStatistic->setLeftUsd($cryptoCurrency->getPriceUsd()*$orderStatistic->getLeftCount());
						}
					}
					$em->persist($orderStatistic);
				}
				$em->flush();
			}
			return true;
		} catch (Exception $exception) {
			$this->errors[] = $exception->getMessage();
			return false;
		}
	}
	/*public function updateOrderHistoryYobit(){
		$yobitClient = new YobitClient();
		$orderHistoryYobit = $yobitClient->getTradeHistory(array('pair'=>'ltc_btc'));
		dump($orderHistoryYobit);
		return true;
		if(isset($orderHistoryCriptopia['success'])){
			if($orderHistoryCriptopia['success'] == true){
				$currency = $this->get('app.service.currency');
				$em = $this->getDoctrine()->getManager();
				foreach ($orderHistoryCriptopia['result'] as $k => $v) {
					$orderHistory = $currency->apiCryptopiaToObjectOrderHistory($v);
					if ($orderHistory === false) {
						$this->errors[] = $currency->getErrors();
						return false;
					}
					if (!empty($orderHistory)) {
						$em->persist($orderHistory);
						$em->flush();
					}
				}

			}else{
				$this->errors[]= $orderHistoryCriptopia['message'];
				return false;
			}
		}else{
			$this->errors[]= 'Помилка під час отримання даних із Cryptopia!';
			return false;
		}
		return true;

	}*/
}

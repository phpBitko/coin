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
use AppBundle\Service\HitBTCClient\HitBTC;
use AppBundle\Service\LiquiClient\Liqui;
use AppBundle\Service\YobitClient;
use AppBundle\Entity\OrderStatistic;

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
			//Отримуємо дані із Бітрікса
			$updateOrderHistoryBittrex = $this->updateOrderHystoryBittrex();
			if ($updateOrderHistoryBittrex === false) {
				$this->isErrors = true;
			}

			$updateOrderHistoryLiqui = $this->updateOrderHistoryLiqui();
			if ($updateOrderHistoryLiqui === false) {
				$this->isErrors = true;
			}

			$updateOrderHistoryCryptopia = $this->updateOrderHistoryCryptopia();
			if ($updateOrderHistoryCryptopia === false) {
				$this->isErrors = true;
			}

			/*$updateOrderHistoryYobit = $this->updateOrderHistoryYobit();
			if ($updateOrderHistoryYobit === false) {
				$this->isErrors = true;
			}*/

			$updateOrderHistoryHitBTC = $this->updateOrderHistoryHitBTC();
			if ($updateOrderHistoryHitBTC === false) {
				$this->isErrors = true;
			}

			/*$updateOrderHistoryExmo = $this->updateOrderHistoryExmo();
			if ($updateOrderHistoryExmo === false) {
				$this->isErrors = true;
			}*/

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

	private function updateOrderHystoryBittrex() {
		$bittrexClient = new BittrexClient();
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
		$liquiClient = new Liqui();
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
		$hitBTCClient = new HitBTC();
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
		dump($orderHistoryExmo );
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
		$cryptopiaClient = new CryptopiaClient();
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

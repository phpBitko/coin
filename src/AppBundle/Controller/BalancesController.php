<?php
// src/AppBundle/Controller/CRUDController.php

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
use AppBundle\Entity\Balances;

use AppBundle\Service\ExmoClient;
use Symfony\Component\Security\Acl\Exception\Exception;

/**
 *
 * @Route("/balances")
 *
 */
class BalancesController extends Controller
{
	protected $isErrors = false;
	protected $errors = array();

	/**
	 * @return Response $response
	 *
	 * @Route("/updateBalances", name="update_balances",  options={"expose"=true})
	 * @Method("POST")
	 *
	 */
	public function updateAction() {
		try {
			$em = $this->getDoctrine()->getManager();
			$activeBallances =
				$em->getRepository('AppBundle:Balances')->findBy(array('isActive' => true));
			//Робимо всі записи не активні
			$oldBallances =
				$em->getRepository('AppBundle:Balances')->findBy(array('isActive' => true, 'myBalance' => false));
			foreach ($oldBallances as $k => $v) {
				$v->setIsActive(false);
				$em->persist($v);
			}

			/*//Отримуємо дані із Полонекса
			$updatePolonex = $this->updatePolonex();
			if ($updatePolonex === false) {
				$this->isErrors = true;
			}*/

			//Отримуємо дані із Лікві
			$updateLiqui = $this->updateLiqui($activeBallances);
			if ($updateLiqui === false) {
				$this->isErrors = true;
			}

			//Отримуємо дані із Йобіт
			$updateYobit = $this->updateYobit($activeBallances);
			if ($updateYobit === false) {
				$this->isErrors = true;
			}

			//Отримуємо дані із ХітБТС
			$updateHitBTC = $this->updateHitBTC($activeBallances);
			if ($updateHitBTC === false) {
				$this->isErrors = true;
			}

			//Отримуємо дані із Кріптопії
			$updateCryptopia = $this->updateCryptopia($activeBallances);
			if ($updateCryptopia === false) {
				$this->isErrors = true;
			}

			//Отримуємо дані із Ексмо
			$updateExmo = $this->updateExmo($activeBallances);
			if ($updateExmo === false) {
				$this->isErrors = true;
			}

			//Отримуємо дані із Бітрікса
			$updateBittrex = $this->updateBittrex($activeBallances);
			if ($updateBittrex === false) {
				$this->isErrors = true;
			}


			$myBallances = $em->getRepository('AppBundle:Balances')->findBy(array('myBalance' => true));

			if(!empty($myBallances)){
				$currency = $this->get('app.service.currency');
				foreach ($myBallances as $myBallance){
					$myBallance = $currency->addPrice($myBallance);
					$em->persist($myBallance);
				}
			}

			$statistic = new Statistic();

			//Додаємо статистику
			$ballances = $em->getRepository('AppBundle:Balances')->findBy(array('isActive' => true));
			$lastStatistic = $em->getRepository('AppBundle:Statistic')->findOneBy(array(), array('id'=>'DESC'));
			$usdBallanceFarm1 = 0;
			$usdBallanceFarm2 = 0;
			$usdBallance = 0;
			$btcBallance = 0;
			foreach ($ballances as $ballance){
				$usdBallanceFarm1 += $ballance->getFarm1()/100*$ballance->getPriceUsd();
				$usdBallanceFarm2 += $ballance->getFarm2()/100*$ballance->getPriceUsd();
				$usdBallance += $ballance->getPriceUsd();
				$btcBallance += $ballance->getPriceBtc();
			}
			$statistic->setPriceUsdFarm1($usdBallanceFarm1);
			$statistic->setPriceUsdFarm2($usdBallanceFarm2);
			$statistic->setPriceUsd($usdBallance);
			$statistic->setPriceBtc($btcBallance);
			$statistic->setProfit($usdBallance - $lastStatistic->getPriceUsd());
			$em->persist($statistic);
			$em->flush();

			if ($this->isErrors === true) {
				$message = implode('', $this->errors);
			} else {
				$message = 'Дані успішно оновлені!';
			}

			return new JsonResponse(array('message' => $message), Response::HTTP_OK);
		} catch (\Exception $exception) {
			return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
		}
	}

	private function updateBittrex($activeBallances = null) {
		$apiKey = $this->getParameter('app_bundle.bittrex_api_key');
		$apiSecret = $this->getParameter('app_bundle.bittrex_secret_key');
		$bittrexClient = new BittrexClient($apiKey, $apiSecret);
		$balanceBittrex = $bittrexClient->getBalances();
		$currency = $this->get('app.service.currency');

		if (empty($balanceBittrex)) {
			$this->errors[] = 'Баланс на Bittrex не знайдено!';
			return false;
		} else {
			$em = $this->getDoctrine()->getManager();
			foreach ($balanceBittrex as $k => $v) {
				$balances = $currency->apiBittrexToObjectBalances($v, $activeBallances);
				if ($balances === false) {
					$this->errors[] = $currency->getErrors();
					return false;
				}
				if (!empty($balances->getBalance()) || !empty($balances->getAvailable())
					|| !empty($balances->getPending())
				) {
					if($activeBallances !== null){
						$balances = $this->setFarms($activeBallances, $balances);
						//Вичисляємо і записуємо профіт
						$balances = $this->setProfit($activeBallances, $balances);
					}
					$em->persist($balances);
					//$em->flush();

				} else {
					unset($balances);
				}
			}
		}

		return true;
	}

	private function setProfit($activeBallances, $balancesCurrent){
		foreach ($activeBallances as $ballOne){
			if($balancesCurrent->getStockExchange() == 'мій'){
				dump($balancesCurrent);
			}
			if($ballOne->getCurrency() == $balancesCurrent->getCurrency() && $ballOne->getStockExchange() == $balancesCurrent->getStockExchange()){
				$balancesCurrent->setProfit($balancesCurrent->getPriceUsd() - $ballOne->getPriceUsd());
				if($balancesCurrent->getStockExchange() == 'мій'){
					dump($ballOne);
					dump($balancesCurrent->getPriceUsd());
					dump($ballOne->getPriceUsd());
					dump($balancesCurrent);
				}
				return $balancesCurrent;
			}
		}
		return $balancesCurrent;
	}

	private function updateCryptopia($activeBallances = null) {
		$apiKey = $this->getParameter('app_bundle.cryptopia_api_key');
		$apiSecret = $this->getParameter('app_bundle.cryptopia_secret_key');
		$cryptopiaClient = new CryptopiaClient($apiKey, $apiSecret);
		$balanceCryptopia = $cryptopiaClient->getBalances();
		$currency = $this->get('app.service.currency');

		if (isset($balanceCryptopia['success']) && $balanceCryptopia['success'] === true) {
			$em = $this->getDoctrine()->getManager();
			foreach ($balanceCryptopia['result'] as $k => $v) {
				$balances = $currency->apiCryptopiaToObjectBalances($v);
				if ($balances === false) {
					$this->errors[] = $currency->getErrors();

					return false;
				}
				if (!empty($balances->getBalance()) || !empty($balances->getAvailable())
					|| !empty($balances->getPending())
				) {
					if($activeBallances !== null){
						$balances = $this->setFarms($activeBallances, $balances);
						$balances = $this->setProfit($activeBallances, $balances);
					}
					$em->persist($balances);
					//$em->flush();

				} else {
					unset($balances);
				}
			}
		} else {
			$this->errors[] = 'Баланс на Cryptopia не знайдено! '.$balanceCryptopia['message'];

			return false;
		}

		return true;
	}

	private function updatePolonex() {
		$apiKey = $this->getParameter('app_bundle.polonex_api_key');
		$apiSecret = $this->getParameter('app_bundle.polonex_secret_key');
		$polonexClient = new PolonexClient($apiKey, $apiSecret);
		$balancePolonex = $polonexClient->get_volume();

		$currency = $this->get('app.service.currency');

		if (isset($balanceCryptopia['success']) && $balanceCryptopia['success'] === true) {
			$em = $this->getDoctrine()->getManager();
			foreach ($balanceCryptopia['result'] as $k => $v) {
				$balances = $currency->apiCryptopiaToObjectBalances($v);
				if ($balances === false) {
					$this->errors[] = $currency->getErrors();

					return false;
				}
				if (!empty($balances->getBalance()) || !empty($balances->getAvailable())
					|| !empty($balances->getPending())
				) {
					$em->persist($balances);
					$em->flush();

				} else {
					unset($balances);
				}
			}
		} else {
			$this->errors[] = 'Баланс на Cryptopia не знайдено! '.$balanceCryptopia['message'];

			return false;
		}

		return true;
	}

	private function updateHitBTC($activeBallances =null) {
		$apiKey = $this->getParameter('app_bundle.hitbtc_api_key');
		$apiSecret = $this->getParameter('app_bundle.hitbtc_secret_key');
		$hitBTCClient = new HitBTC($apiKey, $apiSecret);
		$balanceHitBtc = $hitBTCClient->getBalances(true);
		$currency = $this->get('app.service.currency');

		if (empty($balanceHitBtc)) {
			$this->errors[] = 'Баланс на HitBTC не знайдено!';
			return false;
		} else {
			$em = $this->getDoctrine()->getManager();
			foreach ($balanceHitBtc as $k => $v) {
				$balances = $currency->apiHitBTCToObjectBalances($v);
				if ($balances === false) {
					$this->errors[] = $currency->getErrors();

					return false;
				}
				if($activeBallances !== null){
					$balances = $this->setFarms($activeBallances, $balances);
					$balances = $this->setProfit($activeBallances, $balances);
				}
				$em->persist($balances);
				//$em->flush();
			}
		}

		return true;
	}

	private function updateYobit($activeBallances = null) {
		$apiKey = $this->getParameter('app_bundle.yobit_api_key');
		$apiSecret = $this->getParameter('app_bundle.yobit_secret_key');
		$yobitClient = new YobitClient($apiKey, $apiSecret);
		$balanceYobit = $yobitClient->getTradeInfo();
		$currency = $this->get('app.service.currency');
		if (($balanceYobit['success'] != 1)) {
			$this->errors[] = 'Баланс на Yobit не знайдено!';
			return false;
		} else {
			$em = $this->getDoctrine()->getManager();
			foreach ($balanceYobit['return']['funds_incl_orders'] as $k => $v) {
				if ($v > 0) {
					$balances = new Balances();
					if($k == 'usd'){
						$balances->setCurrency('USDT');
					}else{
						$balances->setCurrency(strtoupper($k));
					}
					$balances->setBalance($v);
					$balances->setIsActive(true);
					$balances->setStockExchange('Yobit');
					$balances = $currency->addPrice($balances);
					if($activeBallances !== null){
						$balances = $this->setFarms($activeBallances, $balances);
						$balances = $this->setProfit($activeBallances, $balances);
					}
					$em->persist($balances);
					//$em->flush();
				}
			}
		}

		return true;
	}

	private function setFarms($activeBallances, $balancesCurrent){
		foreach ($activeBallances as $activeBallance){
			if($activeBallance->getCurrency() == $balancesCurrent->getCurrency() && $activeBallance->getStockExchange() == $balancesCurrent->getStockExchange()){
				$balancesCurrent->setFarm1($activeBallance->getFarm1());
				$balancesCurrent->setFarm2($activeBallance->getFarm2());
				return $balancesCurrent;
			}
		}
		return $balancesCurrent;
	}


	private function updateLiqui($activeBallances = null) {
		$apiKey = $this->getParameter('app_bundle.liqui_api_key');
		$apiSecret = $this->getParameter('app_bundle.liqui_secret_key');
		$liquiClient = new Liqui($apiKey, $apiSecret);
		$balanceLiqui = $liquiClient->getBalances();

		$currency = $this->get('app.service.currency');
		if (isset($balanceLiqui['success']) && $balanceLiqui['success'] != 1) {
			$this->errors[] = 'Баланс на Liqui не знайдено!';
			return false;
		} else {
			$em = $this->getDoctrine()->getManager();
			foreach ($balanceLiqui as $k => $v) {
				$balances = new Balances();
				$balances->setCurrency(strtoupper($k));
				$balances->setBalance($v);
				$balances->setIsActive(true);
				$balances->setStockExchange('Liqui');
				$balances = $currency->addPrice($balances);
				if($activeBallances !== null){
					$balances = $this->setFarms($activeBallances, $balances);
					$balances = $this->setProfit($activeBallances, $balances);
				}
				$em->persist($balances);
				//$em->flush();
			}
		}

		return true;
	}

	private function updateExmo($activeBallances = null) {
		try {
			$apiKey = $this->getParameter('app_bundle.exmo_api_key');
			$apiSecret = $this->getParameter('app_bundle.exmo_secret_key');
			$exmoClient = new ExmoClient($apiKey, $apiSecret);
			$balanceExmo = $exmoClient->getUserInfo();
			$em = $this->getDoctrine()->getManager();
			$currency = $this->get('app.service.currency');
			foreach ($balanceExmo['balances'] as $k => $v) {
				if($v != '0'){
					$balances = new Balances();
					if($k == 'USD'){
						$balances->setCurrency('USDT');
					}else{
						$balances->setCurrency(strtoupper($k));
					}
					$balances->setBalance($v);
					if ($balanceExmo['reserved'][$k] != '0') {
						$balances->setAvailable($balanceExmo['reserved'][$k]);
					}

					$balances->setIsActive(true);
					$balances->setStockExchange('Exmo');
					$balances = $currency->addPrice($balances);
					if($activeBallances !== null){
						$balances = $this->setFarms($activeBallances, $balances);
						$balances = $this->setProfit($activeBallances, $balances);
					}
					$em->persist($balances);
					//$em->flush();
				}
			}
			return true;
		}catch(\Exception $exception) {
			$this->errors[] = 'Баланс на Exmo не знайдено!'.$exception->getMessage();
			return false;
		}
	}


}
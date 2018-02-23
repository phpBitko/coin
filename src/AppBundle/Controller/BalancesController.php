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
//use AppBundle\Service\HitBTCClient\HitBTC;
//use AppBundle\Service\LiquiClient\Liqui;
use AppBundle\Service\YobitClient;
use AppBundle\Entity\Balances;
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
			$stockExchange = $em->getRepository('AppBundle:StockExchange')->findBy(['isActive' => true]);
			$currency = $this->get('app.service.currency');
			foreach ($stockExchange as $exchange){
				$apiKey = $this->getParameter('app_bundle.'.lcfirst($exchange->getName()).'_api_key');
				$apiSecret = $this->getParameter('app_bundle.'.lcfirst($exchange->getName()).'_secret_key');
				switch (lcfirst($exchange->getName())){
				case 'kucoin':
					$exchangeClass = new kucoin(array('apiKey'=>$apiKey, 'secret'=>$apiSecret));
					break;
				case 'bittrex':
					$exchangeClass = new bittrex(array('apiKey'=>$apiKey, 'secret'=>$apiSecret));
					break;
				case 'yobit':
					$exchangeClass = new yobit(array('apiKey'=>$apiKey, 'secret'=>$apiSecret));
					break;
				case 'hitbtc':
					$exchangeClass = new hitbtc2(array('apiKey'=>$apiKey, 'secret'=>$apiSecret));
					break;
				case 'cryptopia':
					$exchangeClass = new cryptopia(array('apiKey'=>$apiKey, 'secret'=>$apiSecret));
					break;
				case 'exmo':
					$exchangeClass = new exmo(array('apiKey'=>$apiKey, 'secret'=>$apiSecret));
					break;
				case 'liqui':
					$exchangeClass = new liqui(array('apiKey'=>$apiKey, 'secret'=>$apiSecret));
					break;
				}

				if(!isset($exchangeClass)){
					throw new Exception("Біржа ".$exchange->getName()." не знайдена!");
				}
				$balance = $exchangeClass->fetch_total_balance();

				$balanceEntities = $currency->apiBalancesToObjectBalances($balance, $activeBallances, $exchange->getName());

				if(!$balanceEntities){
					throw new Exception($currency->getErrors());
				}
			}
			$myBallances = $em->getRepository('AppBundle:Balances')->findBy(array('myBalance' => true));

			if(!empty($myBallances)){
				$currency = $this->get('app.service.currency');
				foreach ($myBallances as $myBallance){
					$myBallance = $currency->addPrice($myBallance);
					$em->persist($myBallance);
				}
			}
			$em->flush();
			$users = $em->getRepository('ApplicationSonataUserBundle:User')->findAll();
			$statistics = array();
			if(!empty($users)){
				foreach ($users as $user){
					$statistic = $this->addStatistic($user->getId());
					if($statistic === false){
						throw new Exception(implode('! ', $this->errors));
					}elseif($statistic !== true){
						array_push($statistics, $statistic);
					}
				}
			}

//Отримуємо дані із Йобіт
			/*$updateYobit = $this->updateYobit($activeBallances);
			if ($updateYobit === false) {
				$this->isErrors = true;
			}*/
			//Додаємо статистику

			//Отримуємо дані із Лікві
			/*$updateLiqui = $this->updateLiqui($activeBallances);
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
			$em->flush();*/

			if ($this->isErrors === true) {
				$message = implode('', $this->errors);
			} else {

				$em->flush();
				$message = 'Дані успішно оновлені!';
				if(!empty($statistics)){
					$api = $this->container->get('bo_shurik_telegram_bot.api');
					foreach ($statistics as $statistic){
						$messageTelegram = '';
						switch ($statistic->getIdUsers()->getId()){
						case (2):
							$idChat = 546508533;
							$messageTelegram .= "Всього: ". round($statistic->getPriceUsd(),2)."$ \n";
							$messageTelegram .= "Профіт: ". round($statistic->getProfit(),2)."$";
							break;
						case (3):
							$idChat = 378405364;
							$messageTelegram .= "Загальний: ". round($statistics[0]->getPriceUsd(),2)."$, ".round($statistics[0]->getPriceBtc(),2)."BTC \n";
							$messageTelegram .= "Профіт: ". round($statistics[0]->getProfit(),2)."$ \n";
							$messageTelegram .= "Мій: ". round($statistic->getPriceUsd(),2)."$, ".round($statistic->getPriceBtc(),2)."BTC \n";
							$messageTelegram .= "Профіт: ". round($statistic->getProfit(),2)."$";
							break;
						case (4):
							$idChat = 375052531;
							foreach ($statistics as $statistic1){
								if($statistic1->getIdUsers()->getId() == 1){
									$messageTelegram .= "Загальний: ". round($statistic1->getPriceUsd(),2)."$, ".round($statistic1->getPriceBtc(),2)."BTC \n";
									$messageTelegram .= "Профіт: ". round($statistic1->getProfit(),2)."$ \n";
								}
							}
							$messageTelegram .= "Мій: ". round($statistic->getPriceUsd(),2)."$, ".round($statistic->getPriceBtc(),2)."BTC \n";
							$messageTelegram .= "Профіт: ". round($statistic->getProfit(),2)."$";
							break;
						}
						if(!empty($idChat)){
							$api->sendMessage($idChat,$messageTelegram);
							unset($idChat);
						}
					}
				}
			}

			return new JsonResponse(array('message' => $message), Response::HTTP_OK);
		} catch (\Exception $exception) {
			return new JsonResponse(array('error' => $exception->getMessage()), Response::HTTP_BAD_REQUEST);
		}
	}


	/**
	 * @return bool
	 */
	private function addStatistic($idUser) {
		try {
			$statistic = new Statistic();
			$em = $this->getDoctrine()->getManager();
			$ballances = $em->getRepository('AppBundle:Balances')->findBy(array('isActive' => true, 'idUsers' => $idUser));
			if(empty($ballances)){
				return true;
			}
			$lastStatistic = $em->getRepository('AppBundle:Statistic')->findOneBy(array('idUsers' => $idUser), array('id' => 'DESC'));
			$usdBallanceFarm1 = 0;
			$usdBallanceFarm2 = 0;
			$usdBallance = 0;
			$btcBallance = 0;
			foreach ($ballances as $ballance) {
				$usdBallanceFarm1 += $ballance->getFarm1() / 100 * $ballance->getPriceUsd();
				$usdBallanceFarm2 += $ballance->getFarm2() / 100 * $ballance->getPriceUsd();
				$usdBallance += $ballance->getPriceUsd();
				$btcBallance += $ballance->getPriceBtc();
			}
			$statistic->setPriceUsdFarm1($usdBallanceFarm1);
			$statistic->setPriceUsdFarm2($usdBallanceFarm2);
			$statistic->setPriceUsd($usdBallance);
			$statistic->setPriceBtc($btcBallance);
			if(empty($lastStatistic)){
				$statistic->setProfit($usdBallance);
			}else{
				$statistic->setProfit($usdBallance - $lastStatistic->getPriceUsd());
			}
			$statistic->setIdUsers($em->getRepository('ApplicationSonataUserBundle:User')->find($idUser));
			$em->persist($statistic);
			return $statistic;
		} catch (Exception $exception) {
			$this->errors[] = $exception->getMessage();
			return false;
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
			if($ballOne->getCurrency() == $balancesCurrent->getCurrency() && $ballOne->getStockExchange() == $balancesCurrent->getStockExchange()){
				$balancesCurrent->setProfit($balancesCurrent->getPriceUsd() - $ballOne->getPriceUsd());
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

	/*private function updateYobit($activeBallances = null) {
		$apiKey = $this->getParameter('app_bundle.yobit_api_key');
		$apiSecret = $this->getParameter('app_bundle.yobit_secret_key');
		$yobitClient = new YobitClient($apiKey, $apiSecret);
		$balanceYobit = $yobitClient->getTradeInfo();
		dump($balanceYobit);
		return;
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
	}*/

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


	/*private function updateLiqui($activeBallances = null) {
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
	}*/

	/*private function updateExmo($activeBallances = null) {
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
	}*/


}
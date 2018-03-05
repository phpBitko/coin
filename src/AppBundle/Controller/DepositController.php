<?php

namespace AppBundle\Controller;

use AppBundle\Service\CryptopiaClient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Acl\Exception\Exception;
use AppBundle\Entity\CryptoCurrency;
use AppBundle\Service\BittrexClient;
use AppBundle\Entity\Balances;
use AppBundle\Service\DepositServices;

/**
 *
 * @Route("/deposit")
 *
 */
class DepositController extends Controller
{

	/**
	 * @return Response $response
	 *
	 * @Route("/update", name="update_deposit",  options={"expose"=true})
	 * @Method("POST")
	 *
	 */
	public function updateAction() {
		try{
			$em = $this->getDoctrine()->getManager();
			$this->updateDepositCryptopia();
			$this->updateDepositBittrex();
			$em->flush();
			return new JsonResponse(array('message' => 'Дані успішно оновлено!'), Response::HTTP_OK);
		}catch (\Exception $exception){
			return new JsonResponse(array('error' => $exception->getMessage()), Response::HTTP_BAD_REQUEST);
		}
	}


	public function updateDepositBittrex() {
		$apiKey = $this->getParameter('app_bundle.bittrex_api_key');
		$apiSecret = $this->getParameter('app_bundle.bittrex_secret_key');
		$bittrexClient = new BittrexClient($apiKey, $apiSecret);
		$depositHistory = $bittrexClient->getDepositHistory();
		if(!is_array($depositHistory)){
			throw new \Exception('Помилка отримання депозиту з Bittrex');
		}
		$depositServices = $this->get('app.service.deposit');
		$depositServices->addBittrexDeposit($depositHistory);
		return true;
	}

	public function updateDepositCryptopia() {
		$apiKey = $this->getParameter('app_bundle.cryptopia_api_key');
		$apiSecret = $this->getParameter('app_bundle.cryptopia_secret_key');
		$cryptopiaClient = new CryptopiaClient($apiKey, $apiSecret);
		$depositHistory = $cryptopiaClient->getTransactions(array('Type'=>'Deposit'));
		if($depositHistory['success'] !== true){
			throw new \Exception('Помилка отримання депозиту з Cryptopia');
		}
		$depositServices = $this->get('app.service.deposit');
		$depositServices->addCryptopiaDeposit($depositHistory['result']);
		return true;
	}


}
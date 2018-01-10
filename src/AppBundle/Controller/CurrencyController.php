<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Acl\Exception\Exception;
use AppBundle\Entity\CryptoCurrency;
use AppBundle\Service\Currency;
use AppBundle\Entity\Balances;

/**
 *
 * @Route("/Currency")
 *
 */
class CurrencyController extends Controller
{
	/**
	 * @param Request $request
	 *
	 * @return Response $response
	 *
	 * @Route("/updateAllCurrency", name="update_all_currency",  options={"expose"=true})
	 * @Method("POST")
	 *
	 */
	public function updateAllCurrencyAction(Request $request) {
		try {
			//return new JsonResponse(array('message'=>'Дані успішно оновлено'), Response::HTTP_OK);
			$data = $request->request->get('data');
			$em = $this->getDoctrine()->getManager();

			foreach ($data as $k => $v) {
				$currency = $this->get('app.service.currency');
				$cryptoCurrency = $em->getRepository('AppBundle:CryptoCurrency')->findOneBy(array('name'=> $v['name']));
				if(!empty($cryptoCurrency)){
					$cryptoCurrency = $currency->apiArrayToObject($v, $cryptoCurrency);
				}else{
					$cryptoCurrency = $currency->apiArrayToObject($v);
				}
				if($cryptoCurrency === false){
					throw new Exception($currency->getErrors());
				}
				$balances = $em->getRepository('AppBundle:Balances')->findOneBy(array('currency'=> $cryptoCurrency->getSymbol(), 'isActive'=>true));
				if(!empty($balances)){
					$cryptoCurrency->setIsMyCurrency(true);
				}else{
					$cryptoCurrency->setIsMyCurrency(false);
				}
				$em->persist($cryptoCurrency);
				unset($cryptoCurrency);
			}
			$em->flush();
			return new JsonResponse(array('message'=>'Дані успішно оновлено'), Response::HTTP_OK);
		} catch (\Exception $exception) {
			return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
		}
	}
}

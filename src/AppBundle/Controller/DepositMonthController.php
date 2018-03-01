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
use AppBundle\Service\BittrexClient;
use AppBundle\Entity\Balances;
use AppBundle\Service\DepositServices;

/**
 *
 * @Route("/depositMonth")
 *
 */
class DepositMonthController extends Controller
{

	/**
	 * @return Response $response
	 *
	 * @Route("/update", name="update_deposit_month",  options={"expose"=true})
	 * @Method("POST")
	 *
	 */
	public function updateAction() {
		try{
			$em = $this->getDoctrine()->getManager();
			$depMonth =	$em->getRepository('AppBundle:DepositMonth')->findBy(array('isActive' => true));
			foreach ($depMonth as $k => $v) {
				$v->setIsActive(false);
				$em->persist($v);
			}
			$gorupDepositMonth = $em->getRepository('AppBundle:DepositStatistic')->groupDepositStatistic();
			$depositServices = $this->get('app.service.deposit');
			$depositServices->addDepositMonth($gorupDepositMonth);
			$em->flush();
			return new JsonResponse(array('message' => 'Дані успішно оновлено!'), Response::HTTP_OK);
		}catch (\Exception $exception){
			return new JsonResponse(array('error' => $exception->getMessage()), Response::HTTP_BAD_REQUEST);
		}
	}
}
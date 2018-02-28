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
 * @Route("/depositStatistic")
 *
 */
class DepositStatisticController extends Controller
{

	/**
	 * @return Response $response
	 *
	 * @Route("/update", name="update_deposit_statistic",  options={"expose"=true})
	 * @Method("POST")
	 *
	 */
	public function updateAction() {
		try{
			$em = $this->getDoctrine()->getManager();
			$gorupDeposit = $em->getRepository('AppBundle:Deposit')->groupDepositByMonth();
			$depositServices = $this->get('app.service.deposit');
			$depositServices->addDepositStatistic($gorupDeposit);
			$myDepositStatistics = 	$em->getRepository('AppBundle:DepositStatistic')->findBy(array('isActive' => true, 'isMyDeposit' => true));
			$depositServices->updateMyDepositStatistic($myDepositStatistics);
			$em->flush();
			return new JsonResponse(array('message' => 'Дані успішно оновлено!'), Response::HTTP_OK);
		}catch (\Exception $exception){
			return new JsonResponse(array('error' => $exception->getMessage()), Response::HTTP_BAD_REQUEST);
		}
	}




}
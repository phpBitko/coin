<?php
// src/AppBundle/Controller/CRUDController.php

namespace AppBundle\Controller;

use AppBundle\Service\Currency;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Service\BittrexClient;
use Symfony\Component\Security\Acl\Exception\Exception;

class BalancesCRUDController extends Controller
{

	public function cloneAction($id) {
		$object = $this->admin->getSubject();

		if (!$object) {
			throw new NotFoundHttpException(sprintf('unable to find the object with id: %s', $id));
		}

		// Be careful, you may need to overload the __clone method of your object
		// to set its id to null !
		$clonedObject = clone $object;

		$clonedObject->setCurrency($object->getCurrency().' (Clone)');

		$this->admin->create($clonedObject);

		$this->addFlash('sonata_flash_success', 'Cloned successfullysss');

		return new RedirectResponse($this->admin->generateUrl('list'));

		// if you have a filtered list and want to keep your filters after the redirect
		// return new RedirectResponse($this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters())));
	}

	public function preList(Request $request) {
		if ($listMode = $request->get('_list_mode')) {
			$this->admin->setListMode($listMode);
		}
		$datagrid = $this->admin->getDatagrid();
		$em = $this->getDoctrine()->getManager();
		$filters = $this->admin->getFilterParameters();
		//Якщо адмін, працюють всі фільтри і по всім юзерам
		if($this->isGranted('ROLE_ADMIN')){
			if(isset($filters['idUsers']) && !empty($filters['idUsers']['value'])){
				$lastBalances =
					$em->getRepository('AppBundle:Statistic')-> findOneBy(array('idUsers'=>$filters['idUsers']['value']),array('id' => 'DESC'));
			}else{
				$lastBalances =
					$em->getRepository('AppBundle:Statistic')-> findOneBy(array('idUsers'=>1),array('id' => 'DESC'));
			}
		}else{
			//Для звичайних юзерів бачать статистику тільки по собі
			$lastBalances =
				$em->getRepository('AppBundle:Statistic')-> findOneBy(array('idUsers'=>$this->getUser()->getId()),array('id' => 'DESC'));
			$filters['idUsers']['value'] = $this->getUser()->getId();
		}

		$balances =
			$em->getRepository('AppBundle:Balances')-> getBalance($filters);
		$data = [];
		$data['priceUsd'] = $balances['priceUSD'];
		$data['priceBtc'] = $balances['priceBTC'];
		if(!empty($lastBalances)){
			$data['profit'] = $lastBalances->getProfit();
		}else{
			$data['profit'] = '';
		}

		/*$orderHistoryes = $datagrid->getResults();
		$data = [];
		$data['price'] = 0;
		$data['priceUsd'] = 0;
		$data['quantity'] = 0;
		$data['currency'] = '';
		if(isset($orderHistoryes[0])){
			$currency =  explode('-',$orderHistoryes[0]->getExchange());
			$data['currency'] = $currency['0'];
		}
		foreach ($orderHistoryes as $orderHistory){
			if($orderHistory->getOrderType() == 'sell'){
				$data['price'] = $data['price'] + $orderHistory->getPrice();
				$data['priceUsd'] = $data['priceUsd'] + $orderHistory->getPriceUsd();
				$data['quantity'] = $data['quantity'] - $orderHistory->getQuantity();
			}else{
				$data['price'] = $data['price'] - $orderHistory->getPrice();
				$data['priceUsd'] = $data['priceUsd'] - $orderHistory->getPriceUsd();
				$data['quantity'] = $data['quantity'] + $orderHistory->getQuantity();
			}
		}*/

		$formView = $datagrid->getForm()->createView();

		// set the theme for the current Admin Form
		//$this->setFormTheme($formView, $this->admin->getFilterTheme());

		return $this->render($this->admin->getTemplate('list'), array(
			'action' => 'list',
			'form' => $formView,
			'datagrid' => $datagrid,
			'data'=>$data,
			'csrf_token' => $this->getCsrfToken('sonata.batch'),
			'export_formats' => $this->has('sonata.admin.admin_exporter') ?
				$this->get('sonata.admin.admin_exporter')->getAvailableFormats($this->admin) :
				$this->admin->getExportFormats(),
		), null);
	}


}
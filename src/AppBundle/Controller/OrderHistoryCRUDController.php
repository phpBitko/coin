<?php
// src/AppBundle/Controller/CRUDController.php

namespace AppBundle\Controller;

use AppBundle\Service\Currency;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\BittrexClient;
use Symfony\Component\Security\Acl\Exception\Exception;

class OrderHistoryCRUDController extends Controller
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
		dump($this->admin->getDatagrid());
		//$request = $this->getRequest();
		if ($listMode = $request->get('_list_mode')) {
			$this->admin->setListMode($listMode);
		}

		$datagrid = $this->admin->getDatagrid();
		$orderHistoryes = $datagrid->getResults();
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
		}

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
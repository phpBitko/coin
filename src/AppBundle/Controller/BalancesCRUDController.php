<?php
// src/AppBundle/Controller/CRUDController.php

namespace AppBundle\Controller;

use AppBundle\Service\Currency;
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

	public function listAction() {
		return parent::listAction();
	}


}
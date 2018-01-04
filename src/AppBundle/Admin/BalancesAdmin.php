<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class BalancesAdmin extends AbstractAdmin
{
	protected $datagridValues = array(
		'_page'       => 1,
		'_sort_order' => 'ASC', // sort direction
		'_sort_by' => 'stockExchange' // field name
	);


	public function createQuery($context = 'list')
	{
		$query = parent::createQuery($context);
		$rootAlias = $query->getRootAliases()[0];
		$query
			->andWhere($query->expr()->eq($rootAlias . '.isActive', ':IsActive'))
			->setParameter('IsActive', true);
		return $query;
	}

	protected function configureRoutes(RouteCollection $collection)
	{
		$collection->add('clone', $this->getRouterIdParameter().'/clone');
	}

	protected function configureFormFields(FormMapper $formMapper) {
		$formMapper->add('currency', 'text', array('label' => 'Назва валюти'));
		$formMapper->add('balance', null, array('label' => 'Баланс'));
		$formMapper->add('stockExchange', null, array('label'=>'Назва біржі'));
		$formMapper->add('farm1', null, array('label' => 'Ферма №1, %'));
		$formMapper->add('farm2', null, array('label' => 'Ферма №2, %'));
		$formMapper->add('isActive', null, array('label' => 'Активність'));
		$formMapper->add('myBalance', null, array('label' => 'Мій гаманець'));
	}

	protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
		$datagridMapper->add('currency', null/*, array('show_filter'=>true)*/);
		$datagridMapper->add('balance');
	}

	protected function configureListFields(ListMapper $listMapper) {
		$listMapper->addIdentifier('currency', 'text', array('label' => 'Назва валюти'))
			->add('name', null, array('label'=>'Повна назва'))
			->add('balance', null, array('label'=>'Баланс'))
			->add('available', null, array('label'=>'Доступно'))
			->add('pending', null, array('label'=>'В очікуванні'))
			//->add('cryptoAddress', null, array('label'=>'Адреса гаманця'))
			->add('stockExchange', null, array('label'=>'Назва біржі'))
			->add('farm1', null, array('label' => 'Ферма №1, %'))
		    ->add('farm2', null, array('label' => 'Ферма №2, %'))
			->add('addDate', null, array('label'=>'Дата оновлення'))
			->add('priceUsd', null, array('label'=>'Ціна, $'))
			->add('priceBtc', null, array('label'=>'Ціна, BTC'))
			/*->add('_action', null, [
				'actions' => [
					'show' => [],
					'edit' => [],
					'delete' => [],
					'clone' => [
						'template' => 'AppBundle:CRUD:list__action_clone.html.twig'
					]
				]
			])*/;

	}
}
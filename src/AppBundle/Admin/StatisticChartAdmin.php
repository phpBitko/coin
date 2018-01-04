<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class StatisticChartAdmin extends AbstractAdmin
{
	protected $baseRoutePattern = 'statisticCharts';
	protected $baseRouteName = 'statisticCharts';

	protected function configureFormFields(FormMapper $formMapper) {
	/*	$formMapper->add('name', 'text', array('label' => 'Назва'))
			->add('symbol', 'text', array('label' => 'Скорочена назва'))
			->add('isActive', null, array('label' => 'Активність'));*/
	}
	protected function configureRoutes(RouteCollection $collection){
		$collection->clearExcept(['list']);
	}

	protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
		/*$datagridMapper->add('name')->add('symbol', null, array(), null, array('label' => 'Скорочена назва'))
			->add('isActive')
		    ->add('isMyCurrency');*/
	}

	protected function configureListFields(ListMapper $listMapper) {
		$listMapper->addIdentifier('priceUsd', null, array('label' => 'Загальна сума, $'))
			->add('priceBtc', null, array('label' => 'Загальна сума, BTC'))
			->add('priceUsdFarm1', null, array('label' => 'Сума на 1 фермі, $'))
			->add('priceUsdFarm2', null, array('label' => 'Сума на 2 фермі, $'))
			->add('addDate', 'date', array('label' => 'Дата оновлення', 'format' => 'd-m-Y H:i'));
	}
}
<?php

namespace AppBundle\Admin;

use Doctrine\ORM\EntityManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\AdminInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Menu\ItemInterface as MenuItemInterface;
use AppBundle\Entity\OrderHistory;

class OrderHistoryAdmin extends AbstractAdmin
{


	protected $datagridValues = array(
		'_page'       => 1,
		'_sort_order' => 'DESC', // sort direction
		'_sort_by' => 'addDate' // field name
	);

	protected function configureFormFields(FormMapper $formMapper) {
		/*	$formMapper->add('name', 'text', array('label' => 'Назва'))
				->add('symbol', 'text', array('label' => 'Скорочена назва'))
				->add('isActive', null, array('label' => 'Активність'));*/
	}

	protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
		$em = $this->getModelManager()->getEntityManager('AppBundle\Entity\OrderHistory');
		$stockExchangeResult =
			$em->createQueryBuilder('oh')->select('oh.stockExchange')->from('AppBundle\Entity\OrderHistory', 'oh')
				->groupBy('oh.stockExchange')->orderBy('oh.stockExchange', 'ASC')->getQuery()->getResult();
		$stockExchangeChoices = array();
		foreach ($stockExchangeResult as $stockExchangeRow) {
			$stockExchangeChoices[$stockExchangeRow['stockExchange']] = $stockExchangeRow['stockExchange'];
		}

		$exchangeResult =
			$em->createQueryBuilder('oh')->select('oh.exchange')->from('AppBundle\Entity\OrderHistory', 'oh')
				->groupBy('oh.exchange')->orderBy('oh.exchange', 'ASC')->getQuery()->getResult();
		$exchangeChoices = array();
		foreach ($exchangeResult as $exchangeRow) {
			$exchangeChoices[$exchangeRow['exchange']] = $exchangeRow['exchange'];
		}


		$datagridMapper->add('stockExchange', 'doctrine_orm_choice', array(
				'label' => 'Назва біржі',
				'show_filter' => true,
			), 'choice', array(
				'choices' => $stockExchangeChoices,
				'expanded' => false,
				'multiple' => false,
			))->add('exchange', null, array('label' => 'Назва пари', 'show_filter' => true), 'choice', array(
				'choices' => $exchangeChoices,
				'expanded' => false,
				'multiple' => false,
			))
			->add('addDate', 'doctrine_orm_date_range', array('label' => 'Дата', 'show_filter' => true,  'field_type' => 'sonata_type_date_range_picker'), null, array(
				'field_options' => array(
					'format' => 'dd-MM-yyyy'
				)));
	}

	protected function configureListFields(ListMapper $listMapper) {
		$listMapper->add('exchange', null, array('label' => 'Валюти'))->add('addDate', null, array('label' => 'Додано'))
			->add('stockExchange', null, array('label' => 'Біржа'))
			->add('orderType', null, array('label' => 'Тип ордеру'))
			->add('quantity', null, array('label' => 'Кількість'))->add('commission', null, array('label' => 'Комісія'))
			->add('price', null, array('label' => 'Ціна'))
			->add('pricePerUnit', null, array('label' => 'Ціна за одиницю'))
			->add('priceUsd', null, array('label' => 'Ціна, $'))
			->add('priceUsdPerUnit', null, array('label' => 'Ціна за одиницю, $'));
	}
}
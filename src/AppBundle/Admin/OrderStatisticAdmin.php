<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class OrderStatisticAdmin extends AbstractAdmin
{
	protected $datagridValues = array(
		'_page'       => 1,
		'_sort_order' => 'ASC', // sort direction
		'_sort_by' => 'stockExchange' // field name
	);

	protected function configureFormFields(FormMapper $formMapper) {
	/*	$formMapper->add('name', 'text', array('label' => 'Назва'))
			->add('symbol', 'text', array('label' => 'Скорочена назва'))
			->add('isActive', null, array('label' => 'Активність'));*/
	}

	public function createQuery($context = 'list')
	{
		$query = parent::createQuery($context);
		$rootAlias = $query->getRootAliases()[0];
		$query
			->andWhere($query->expr()->eq($rootAlias . '.isActive', ':IsActive'))
			->setParameter('IsActive', true);
		return $query;
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
		));
	}

	protected function configureListFields(ListMapper $listMapper) {
		$listMapper->add('exchange', null, array('label' => 'Назва пари'))
			->add('stockExchange', null, array('label' => 'Назва біржі'))
			->add('sumCrypto', null, array('label' => 'Сума торгів, кріпта','collapse' => array()))
			->add('sumUsd', null, array('label' => 'Сума торгів, $','collapse' => array()))
			->add('leftUsd', null, array('label' => 'Залишилось, $'))
			->add('buyCount', null, array('label' => 'Куплено, кількість'))
			->add('sellCount', null, array('label' => 'Продано, кількість'))
			->add('leftCount', null, array('label' => 'Залишилось, кількість'));
	}
}
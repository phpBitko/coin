<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Doctrine\ORM\EntityRepository;

class DepositStatisticAdmin extends AbstractAdmin
{
	protected $datagridValues = array(
		'_page'       => 1,
		'_sort_order' => 'DESC', // sort direction
		'_sort_by' => 'month' // field name
	);


	public function createQuery($context = 'list')
	{
		$query = parent::createQuery($context);
		$rootAlias = $query->getRootAliases()[0];
		$query->andWhere($query->expr()->eq($rootAlias . '.isActive', ':ISACTIVE'))
			->setParameter('ISACTIVE', true);
		return $query;
	}


	protected function configureFormFields(FormMapper $formMapper) {
		$formMapper->add('idCryptoCurrency','entity', array(
			'class' => 'AppBundle:CryptoCurrency',
			'query_builder' => function (EntityRepository $er) {
				return $er->createQueryBuilder('u')
					->orderBy('u.symbol', 'ASC');
			},
			'choice_label' => 'symbol',
			'label'=>'Навза валюти'))
			->add('month', 'sonata_type_datetime_picker',array('label' => 'Місяць','format' =>'MM-yyyy', 'dp_view_mode' => 'month'))
			->add('amount', null, array('label' => 'Кількість'))
			->add('fromIn', null, array('label' => 'Отримувач'))
			->add('farm1', null, array('label' => 'Ферма1'))
			->add('farm2', null, array('label' => 'Ферма2'))
			->add('farm3', null, array('label' => 'Ферма3'))
			->add('isMyDeposit', null, array('label' => 'Мій депозит'));
	}

	protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
		$em = $this->getModelManager()->getEntityManager('AppBundle\Entity\DepositStatistic');
		$cryptoCurrencyResult =
			$em->createQueryBuilder('c')
				->select('c.symbol')
				->from('AppBundle\Entity\CryptoCurrency', 'c')
				->orderBy('c.symbol', 'ASC')
				->getQuery()
				->getResult();
		$cryptoCurrencyChoices = array();
		foreach ($cryptoCurrencyResult as $cryptoCurrencyRow) {
			$cryptoCurrencyChoices[$cryptoCurrencyRow['symbol']] = $cryptoCurrencyRow['symbol'];
		}

		$datagridMapper->add('idCryptoCurrency.symbol', null, array(
			'label' => 'Назва валюти',
			'show_filter' => true,
		), 'choice', array(
			'choices' => $cryptoCurrencyChoices,
			'expanded' => false,
			'multiple' => false,))

		;
	}

	protected function configureListFields(ListMapper $listMapper) {
		$listMapper->addIdentifier('idCryptoCurrency.symbol', null, array('label' => 'Назва валюти'))
			->add('idCryptoCurrency.name', null, array('label' => 'Повна назва'))
			->add('month', null, array('label' => 'Місяць', 'format' => 'm.Y'))
			->add('amount', null, array('label' => 'Кількість'))
			->add('fromIn', null, array('label' => 'Отримувач'))
			->add('priceUsdPerm', null, array('label' => 'Сума постійна, $'))
			->add('priceUsdActual', null, array('label' => 'Сума актуальна, $'))
			->add('priceBtcPerm', null, array('label' => 'Сума постійна, BTC'))
			->add('priceBtcActual', null, array('label' => 'Сума актуальна, BTC'))
			->add('isMyDeposit', null, array('label' => 'Мій депозит'))
			->add('farm1', null, array('label' => 'Ферма1'))
			->add('farm2', null, array('label' => 'Ферма2'))
			->add('farm3', null, array('label' => 'Ферма3'));
	}
}
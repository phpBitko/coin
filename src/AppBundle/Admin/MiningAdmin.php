<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\Type\ChoiceFieldMaskType;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;

class MiningAdmin extends AbstractAdmin
{
	protected $datagridValues = array(
		'_page'       => 1,
		'_sort_order' => 'DESC', // sort direction
		'_sort_by' => 'startDate' // field name
	);

	protected function configureFormFields(FormMapper $formMapper) {



		$formMapper
			->add('idCurrency','entity', array(
				'class' => 'AppBundle:CryptoCurrency',
				'query_builder' => function (EntityRepository $er) {
					return $er->createQueryBuilder('c')
					->orderBy('c.symbol', 'ASC');
					},
				'choice_label' => 'symbol',
				'label'=>'Валюта')
			)
			->add('startDate', 'sonata_type_date_picker', array('label' => 'Початок майнінгу', 'required' => false, 'format' => 'dd.MM.yyyy HH:m'))
			->add('startBalance', null, array('label' => 'Початковий баланс'))
			->add('endDate', 'sonata_type_date_picker', array('label' => 'Кінець майнінгу','required' => false, 'format' => 'dd.MM.yyyy HH:m'))
			->add('endBalance', null, array('label' => 'Кінцевий баланс'));

		$formMapper->getFormBuilder()->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($formMapper) {
			$mining = $event->getForm()->getData();
			if (!(empty($mining)) && !empty($mining->getStartBalance()) && !empty($mining->getEndBalance())) {
				$mining->setDifferenceBalance($mining->getEndBalance() - $mining->getStartBalance());
				$mining->setDifferenceDate(($mining->getEndDate()->getTimestamp() - $mining->getStartDate()->getTimestamp())/3600);
				$cryptoCurrency = $this->getModelManager()->getEntityManager('AppBundle\Entity\CryptoCurrency');
				$cryptoCoin = $cryptoCurrency->getRepository('AppBundle\Entity\CryptoCurrency')->findById($mining->getIdCurrency());
				$mining->setDifferenceBalanceUsd($cryptoCoin[0]->getPriceUsd()*$mining->getDifferenceBalance());
				$mining->setProfitUsdPerDay($mining->getDifferenceBalanceUsd()/($mining->getDifferenceDate()/24));

			}
			$event->setData($mining);
		});



	}

	protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
		/*$datagridMapper->add('name')->add('symbol', null, array(), null, array('label' => 'Скорочена назва'))
			->add('isActive')
		    ->add('isMyCurrency');*/
	}

	protected function configureListFields(ListMapper $listMapper) {
		$listMapper
			->addIdentifier('idCurrency.symbol', null, array('label' => 'Назва валюти'))
			->add('startDate', null, array('label' => 'Початок майнінгу','format' => 'd.m.Y H:i'))
			->add('startBalance', null, array('label' => 'Початковий баланс'))
			->add('endDate', null, array('label' => 'Кінець майнінгу', 'format' => 'd.m.Y H:i'))
			->add('endBalance', null, array('label' => 'Кінцевий баланс'))
			->add('differenceBalance', null, array('label' => 'Різниця, кріпта'))
			->add('differenceBalanceUsd', null, array('label' => 'Різниця, $'))
			->add('differenceDate', null, array('label' => 'Різниця, годин'))
			->add('profitUsdPerDay', null, array('label' => 'Профіт за день, $'));
	}
}
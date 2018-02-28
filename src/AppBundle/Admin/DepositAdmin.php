<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Doctrine\ORM\EntityRepository;

class DepositAdmin extends AbstractAdmin
{
	protected $datagridValues = array(
		'_page'       => 1,
		'_sort_order' => 'DESC', // sort direction
		'_sort_by' => 'getDate' // field name
	);


	protected function configureFormFields(FormMapper $formMapper) {
		$formMapper->add('currency', 'text', array('label' => 'Назва'))
			->add('isActive', null, array('label' => 'Активність'));
	}

	protected function configureDatagridFilters(DatagridMapper $datagridMapper) {

		/*$datagridMapper->add('idUsers', null, array('label' => 'Користувач', 'show_filter' => true), 'entity', array(
			'class' => 'ApplicationSonataUserBundle:User',
			'choice_label' => 'username',
			'query_builder' => function (EntityRepository $er) {
				$user =
					$this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
				$role = $user->getRoles();
				$role = $role[0];
				$query = $er->createQueryBuilder('u');
				if ($role != 'ROLE_SUPER_ADMIN') {
					$query->andwhere('u.id = '.$user->getId());
				}
				$query->orderBy('u.username', 'DESC');
				return $query;
			}
		));*/
	}

	protected function configureListFields(ListMapper $listMapper) {
		$listMapper->addIdentifier('currency', null, array('label' => 'Назва валюти'))
		->add('currencyName', null, array('label' => 'Повна назва'))
			->add('amount', null, array('label' => 'Кількість'))
			->add('getDate', null, array('label' => 'Дата отримання', 'format' => 'd.m.Y H:i'))
			->add('isActive', null, array('label' => 'Активність'))
			->add('fromIn', null, array('label' => 'Отримувач'))
			->add('cryptoAddress', null, array('label' => 'Адреса'));
	}
}
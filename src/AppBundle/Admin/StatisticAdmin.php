<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Doctrine\ORM\EntityRepository;

class StatisticAdmin extends AbstractAdmin
{
	protected $datagridValues = array(
		'_page'       => 1,
		'_sort_order' => 'DESC', // sort direction
		'_sort_by' => 'addDate' // field name
	);

	public function createQuery($context = 'list')
	{
		$user = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
		$role = $user->getRoles();
		$role = $role[0];
		$query = parent::createQuery($context);
		$rootAlias = $query->getRootAliases()[0];
		//Якщо не супер адмін, то показуємо тільки дані авторизованого користувача.
		if($role != 'ROLE_SUPER_ADMIN'){
			$query->andWhere($query->expr()->eq($rootAlias . '.idUsers', ':IDUSERS'))
				->setParameter('IDUSERS', $user->getId());
		}
		return $query;
	}

	protected function configureFormFields(FormMapper $formMapper) {
	/*	$formMapper->add('name', 'text', array('label' => 'Назва'))
			->add('symbol', 'text', array('label' => 'Скорочена назва'))
			->add('isActive', null, array('label' => 'Активність'));*/
	}

	protected function configureDatagridFilters(DatagridMapper $datagridMapper) {

		$datagridMapper->add('idUsers', null, array('label' => 'Користувач', 'show_filter' => true), 'entity', array(
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
		));
	}

	protected function configureListFields(ListMapper $listMapper) {
		$listMapper->addIdentifier('priceUsd', null, array('label' => 'Загальна сума, $', 'template'=>'AppBundle:StatisticAdmin:customPriceUsdListField.html.twig'))
			->add('priceBtc', null, array('label' => 'Загальна сума, BTC'))
			->add('priceUsdFarm1', null, array('label' => 'Сума на 1 фермі, $','template'=>'AppBundle:StatisticAdmin:customPriceUsdListField.html.twig'))
			->add('priceUsdFarm2', null, array('label' => 'Сума на 2 фермі, $','template'=>'AppBundle:StatisticAdmin:customPriceUsdListField.html.twig'))
			->add('addDate', 'date', array('label' => 'Дата оновлення', 'format' => 'd-m-Y H:i'))
		    ->add('profit', null, array('label' => 'Профіт, $',  'collapse' => array()))
			->add('idUsers.name', null, array('label' => 'Користувач'));
	}
}
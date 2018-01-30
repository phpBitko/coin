<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CryptoCurrancyAdmin extends AbstractAdmin
{
	protected function configureFormFields(FormMapper $formMapper) {
		$formMapper
			->add('name', 'text', array('label' => 'Назва'))
			->add('symbol', 'text', array('label' => 'Скорочена назва'))
			->add('isActive', null, array('label' => 'Активність'));

	}

	protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
		$datagridMapper->add('name')->add('symbol', null, array(), null, array('label' => 'Скорочена назва'))
			->add('isActive')
		    ->add('isMyCurrency', null,  array('label' => 'Мої валюти', 'show_filter' => true));
	}

	protected function configureListFields(ListMapper $listMapper) {
		$listMapper->addIdentifier('name', null, array('label' => 'Назва','header_style' => 'min-width:120px;', 'template'=>'AppBundle:CryptoCurrencyAdmin:customCurrencyListField.html.twig'))
			->add('symbol', 'text', array('label' => 'Скорочена назва',/*'header_class' => 'customActions'*//*, 'label_icon' => 'fa fa-thumbs-o-up'/*,"header_class" =>"col-md-5" */))
			->add('lastUpdated', null, array('label' => 'Дата оновлення', 'format' => 'd.m.Y H:i'))
			->add('priceUsd', null, array('label' => 'Ціна, $','label_icon' => 'fa fa-usd'/* 'identifier'=>true)*/))
			->add('priceBtc', null, array('label' => 'Ціна, BTC', 'label_icon' => 'fa fa-btc'))
			->add('percentChange1h', null, array('label' => 'Зміна ціни за 1 год.,%', 'collapse' => array()))
			->add('percentChange24h', 'float', array('label' => 'Зміна ціни за 24 год., %','collapse' => array()))
			->add('percentChange7d', 'float', array('label' => 'Зміна ціни за 7 днів, %','collapse' => array()))
			->add('volumeUsd24h', null, array('label' => 'Об`єм за 24 год., $'))
			->add('isActive', null, array('label' => 'Активність'))
			->add('isMyCurrency', null, array('label'=>'Мій портфель'));
	}
}
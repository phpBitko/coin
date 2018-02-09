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
use Ob\HighchartsBundle\Highcharts\Highchart;

class StatisticChartCRUDController extends Controller
{
	public function listAction() {
		$em = $this->getDoctrine()->getManager();
		$users = $em->getRepository('AppBundle:Users')->findAll();
		$chart = array();
		if(!empty($users)){
			$i=1;
			foreach ($users as $user){
				$column = $em->getRepository('AppBundle:Statistic')->getColumn(array('priceUsd', 'priceBtc', 'addDate'), $user->getId());
				if(!empty($column)){
					$seriesUsd =
						array(
							'name' => 'Сума, USD',
							//	'type'  => 'spline',
							'color' => '#4572A7',
							'yAxis' => 1,
							'data' => $column['priceUsd']

						);
					$seriesBtc =
						array(
							'name' => 'Сума, BTC',
							//	'type'  => 'spline',
							'color' => '#AA4643',
							'data' => $column['priceBtc']
						);

					$yData = array(
						array(
							'labels' => array(
								'style'     => array('color' => '#AA4643')
							),
							'title' => array(
								'text' => 'Сума, BTC',
								'style' => array('color' => '#AA4643'),
							),

							'opposite' => true
						),
						array(
							'labels' => array(
								'style'     => array('color' => '#4572A7')
							),

							'title' => array(
								'text' => 'Сума, $',
								'style' => array('color' => '#4572A7'),
							),
						)
					);
					$obUsd = new Highchart();
					$obUsd->chart->renderTo('linechart_usd_'.$user->getId());  // The #id of the div where to render the chart
					$obUsd->title->text($user->getName());
					$obUsd->xAxis->title(array('text' => 'Дата'));
					$obUsd->xAxis->categories($column['addDate']);
					$obUsd->yAxis($yData);
					$obUsd->series(array($seriesUsd, $seriesBtc));
					$chart['chart'.$i] = $obUsd;
					$i++;
				}
			}
		}



	/*	$column = $em->getRepository('AppBundle:Statistic')->getColumn(array('priceUsd', 'priceBtc', 'addDate'));
		$seriesUsd =
			array(
				'name' => 'Сума, USD',
			//	'type'  => 'spline',
				'color' => '#4572A7',
				'yAxis' => 1,
				'data' => $column['priceUsd']

		);
		$seriesBtc =
			array(
				'name' => 'Сума, BTC',
			//	'type'  => 'spline',
				'color' => '#AA4643',
				'data' => $column['priceBtc']
		);

		$yData = array(
			array(
				'labels' => array(
					'style'     => array('color' => '#AA4643')
				),
				'title' => array(
					'text' => 'Сума, BTC',
					'style' => array('color' => '#AA4643'),
				),

				'opposite' => true
			),
			array(
				'labels' => array(
					'style'     => array('color' => '#4572A7')
				),

				'title' => array(
					'text' => 'Сума, $',
					'style' => array('color' => '#4572A7'),
				),
			)
		);
		$obUsd = new Highchart();
		$obUsd->chart->renderTo('linechart_usd');  // The #id of the div where to render the chart
		$obUsd->title->text('Загальний баланс');
		$obUsd->xAxis->title(array('text' => 'Дата'));
		$obUsd->xAxis->categories($column['addDate']);
		$obUsd->yAxis($yData);
		$obUsd->series(array($seriesUsd, $seriesBtc));*/
		/*$seriesBtc = array(
			array("name" => "Сума", "data" => $column['priceBtc']),
		);

		$obBtc = new Highchart();
		$obBtc->chart->renderTo('linechart_btc');  // The #id of the div where to render the chart
		$obBtc->title->text('Загальний баланс, BTC');

		$obBtc->xAxis->title(array('text' => "Дата"));

		$obBtc->yAxis->title(array('text' => "Сума, BTC"));
		$obBtc->series($seriesBtc);*/
		dump($chart);
		return $this->render('AppBundle:StatisticAdmin:updateStatisticChart.html.twig', array('charts' => $chart));
	}
}
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
		$column = $em->getRepository('AppBundle:Statistic')->getColumn(array('priceUsd', 'priceBtc'));

		$seriesUsd = array(
			array("name" => "Сума", "data" => $column['priceUsd']),
		);
		$obUsd = new Highchart();
		$obUsd->chart->renderTo('linechart_usd');  // The #id of the div where to render the chart
		$obUsd->title->text('Загальний баланс, USD');
		$obUsd->xAxis->title(array('text' => "Дата"));
		$obUsd->yAxis->title(array('text' => "Сума, $"));
		$obUsd->series($seriesUsd);

		$seriesBtc = array(
			array("name" => "Сума", "data" => $column['priceBtc']),
		);
		$obBtc = new Highchart();
		$obBtc->chart->renderTo('linechart_btc');  // The #id of the div where to render the chart
		$obBtc->title->text('Загальний баланс, BTC');
		$obBtc->xAxis->title(array('text' => "Дата"));
		$obBtc->yAxis->title(array('text' => "Сума, BTC"));
		$obBtc->series($seriesBtc);

		return $this->render('AppBundle:StatisticAdmin:updateStatisticChart.html.twig', array(
			'chart1' => $obUsd,
			'chart2' => $obBtc
		));
	}
}
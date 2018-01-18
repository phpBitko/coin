<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Statistic
 *
 * @ORM\Table(name="statistic")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StatisticRepository")
 */
class Statistic{
	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="price_usd", type="float", nullable=true)
	 */
	private $priceUsd;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="price_btc", type="float", nullable=true)
	 */
	private $priceBtc;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="price_usd_farm1", type="float", nullable=true)
	 */
	private $priceUsdFarm1;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="price_usd_farm2", type="float", nullable=true)
	 */
	private $priceUsdFarm2;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="profit", type="decimal", precision=10, scale=2,  nullable=true)
	 */
	private $profit;

	/**
	 * @return float
	 */
	public function getProfit() {
		return $this->profit;
	}

	/**
	 * @param float $profit
	 */
	public function setProfit($profit) {
		$this->profit = $profit;
	}

	/**
	 * @return int
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId(int $id) {
		$this->id = $id;
	}

	/**
	 * @return float
	 */
	public function getPriceUsd() {
		return $this->priceUsd;
	}

	/**
	 * @param float $priceUsd
	 */
	public function setPriceUsd(float $priceUsd) {
		$this->priceUsd = $priceUsd;
	}

	/**
	 * @return float
	 */
	public function getPriceBtc(){
		return $this->priceBtc;
	}

	/**
	 * @param float $priceBtc
	 */
	public function setPriceBtc(float $priceBtc) {
		$this->priceBtc = $priceBtc;
	}

	/**
	 * @return float
	 */
	public function getPriceUsdFarm1() {
		return $this->priceUsdFarm1;
	}

	/**
	 * @param float $priceUsdFarm1
	 */
	public function setPriceUsdFarm1(float $priceUsdFarm1) {
		$this->priceUsdFarm1 = $priceUsdFarm1;
	}

	/**
	 * @return float
	 */
	public function getPriceUsdFarm2() {
		return $this->priceUsdFarm2;
	}

	/**
	 * @param float $priceUsdFarm2
	 */
	public function setPriceUsdFarm2(float $priceUsdFarm2) {
		$this->priceUsdFarm2 = $priceUsdFarm2;
	}

	/**
	 * @return \DateTime
	 */
	public function getAddDate(): \DateTime {
		return $this->addDate;
	}

	/**
	 * @param \DateTime $addDate
	 */
	public function setAddDate(\DateTime $addDate) {
		$this->addDate = $addDate;
	}

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="add_date", type="datetime", nullable=true)
	 */
	private $addDate;

	/**
	 * Statistic constructor.
	 */
	public function __construct() {
		$this->addDate = new \DateTime('now', new \DateTimeZone('Europe/Kiev'));
	}

}
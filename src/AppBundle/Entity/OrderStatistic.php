<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Statistic
 *
 * @ORM\Table(name="order_statistic")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderStatisticRepository")
 */
class OrderStatistic{
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
	 * @ORM\Column(name="sum_usd", type="float", nullable=true)
	 */
	private $sumUsd;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="sum_crypto", type="float", nullable=true)
	 */
	private $sumCrypto;

	/**
	 * @var text
	 *
	 * @ORM\Column(name="stock_exchange", type="text", nullable=true)
	 */
	private $stockExchange;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="exchange", type="string", length=20, nullable=true)
	 */
	private $exchange;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="sell_count", type="float", nullable=true)
	 */
	private $sellCount;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="buy_count", type="float", nullable=true)
	 */
	private $buyCount;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="left_count", type="float", nullable=true)
	 */
	private $leftCount;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="left_usd", type="float", nullable=true)
	 */
	private $leftUsd;

	/**
	 * @return float
	 */
	public function getLeftUsd(){
		return $this->leftUsd;
	}

	/**
	 * @param float $leftUsd
	 */
	public function setLeftUsd($leftUsd) {
		$this->leftUsd = $leftUsd;
	}

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="is_active", type="boolean", nullable=true)
	 */
	private $isActive;

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return float
	 */
	public function getSumUsd() {
		return $this->sumUsd;
	}

	/**
	 * @param float $sumUsd
	 */
	public function setSumUsd( $sumUsd) {
		$this->sumUsd = $sumUsd;
	}

	/**
	 * @return float
	 */
	public function getSumCrypto(){
		return $this->sumCrypto;
	}

	/**
	 * @param float $sumCrypto
	 */
	public function setSumCrypto( $sumCrypto) {
		$this->sumCrypto = $sumCrypto;
	}

	/**
	 * @return text
	 */
	public function getStockExchange() {
		return $this->stockExchange;
	}

	/**
	 * @param text $stockExchange
	 */
	public function setStockExchange( $stockExchange) {
		$this->stockExchange = $stockExchange;
	}

	/**
	 * @return string
	 */
	public function getExchange(){
		return $this->exchange;
	}

	/**
	 * @param string $exchange
	 */
	public function setExchange($exchange) {
		$this->exchange = $exchange;
	}

	/**
	 * @return float
	 */
	public function getSellCount(){
		return $this->sellCount;
	}

	/**
	 * @param float $sellCount
	 */
	public function setSellCount($sellCount) {
		$this->sellCount = $sellCount;
	}

	/**
	 * @return float
	 */
	public function getBuyCount(){
		return $this->buyCount;
	}

	/**
	 * @param float $buyCount
	 */
	public function setBuyCount($buyCount) {
		$this->buyCount = $buyCount;
	}

	/**
	 * @return float
	 */
	public function getLeftCount(){
		return $this->leftCount;
	}

	/**
	 * @param float $leftCount
	 */
	public function setLeftCount($leftCount) {
		$this->leftCount = $leftCount;
	}

	/**
	 * @return bool
	 */
	public function isIsActive(){
		return $this->isActive;
	}

	/**
	 * @param bool $isActive
	 */
	public function setIsActive($isActive) {
		$this->isActive = $isActive;
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

	public function __construct() {
		$this->addDate = new \DateTime();
	}

}
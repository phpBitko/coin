<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Balances
 *
 * @ORM\Table(name="order_history")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderHistoryRepository")
 */
class OrderHistory{
	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="order_uuid", type="string", length=36, nullable=true)
	 */
	private $orderUuid;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="exchange", type="string", length=20, nullable=true)
	 */
	private $exchange;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="trade_id", type="integer", nullable=true)
	 */
	private $tradeId;

	/**
	 * @return int
	 */
	public function getTradeId(){
		return $this->tradeId;
	}

	/**
	 * @param int $tradeId
	 */
	public function setTradeId($tradeId) {
		$this->tradeId = $tradeId;
	}

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="add_date", type="datetime", nullable=true)
	 */
	private $addDate;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="order_type", type="string", nullable=true)
	 */
	private $orderType;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="limit_order", type="string", nullable=true)
	 */
	private $limitOrder;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="quantity", type="float", nullable=true)
	 */
	private $quantity;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="quantity_remaining", type="float", nullable=true)
	 */
	private $quantityRemaining;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="commission", type="float", nullable=true)
	 */
	private $commission;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="price_usd", type="float", nullable=true)
	 */
	private $priceUsd;


	/**
	 * @var float
	 *
	 * @ORM\Column(name="price_usd_per_unit", type="float", nullable=true)
	 */
	private $priceUsdPerUnit;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="price", type="float", nullable=true)
	 */
	private $price;



	/**
	 * @var float
	 *
	 * @ORM\Column(name="price_per_unit", type="float", nullable=true)
	 */
	private $pricePerUnit;


	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="is_conditional", type="boolean", nullable=true)
	 */
	private $isConditional;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="condition", type="string", nullable=true)
	 */
	private $condition;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="condition_target", type="float", nullable=true)
	 */
	private $conditionTarget;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="immediate_or_cancel", type="boolean", nullable=true)
	 */
	private $immediateOrCancel;

	/**
	 * @var text
	 *
	 * @ORM\Column(name="stock_exchange", type="text", nullable=true)
	 */
	private $stockExchange;

	/**
	 * @return text
	 */
	public function getStockExchange(){
		return $this->stockExchange;
	}

	/**
	 * @param text $stockExchange
	 */
	public function setStockExchange($stockExchange) {
		$this->stockExchange = $stockExchange;
	}

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="closed_date", type="datetime", nullable=true)
	 */
	private $closedDate;

	/**
	 * @return int
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getOrderUuid(){
		return $this->orderUuid;
	}

	/**
	 * @param string $orderUuid
	 */
	public function setOrderUuid($orderUuid) {
		$this->orderUuid = $orderUuid;
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
	 * @return \DateTime
	 */
	public function getAddDate(){
		return $this->addDate;
	}

	/**
	 * @param \DateTime $addDate
	 */
	public function setAddDate(\DateTime $addDate) {
		$this->addDate = $addDate;
	}

	/**
	 * @return string
	 */
	public function getOrderType(){
		return $this->orderType;
	}

	/**
	 * @param string $orderType
	 */
	public function setOrderType($orderType) {
		$this->orderType = $orderType;
	}

	/**
	 * @return string
	 */
	public function getLimitOrder(){
		return $this->limitOrder;
	}

	/**
	 * @param string $limitOrder
	 */
	public function setLimit($limitOrder) {
		$this->limitOrder = $limitOrder;
	}

	/**
	 * @return float
	 */
	public function getQuantity(){
		return $this->quantity;
	}

	/**
	 * @param float $quantity
	 */
	public function setQuantity($quantity) {
		$this->quantity = $quantity;
	}

	/**
	 * @return float
	 */
	public function getQuantityRemaining(){
		return $this->quantityRemaining;
	}

	/**
	 * @param float $quantityRemaining
	 */
	public function setQuantityRemaining($quantityRemaining) {
		$this->quantityRemaining = $quantityRemaining;
	}

	/**
	 * @return float
	 */
	public function getCommission(){
		return $this->commission;
	}

	/**
	 * @param float $commission
	 */
	public function setCommission($commission) {
		$this->commission = $commission;
	}

	/**
	 * @return float
	 */
	public function getPriceUsd(){
		return $this->priceUsd;
	}

	/**
	 * @param float $priceUsd
	 */
	public function setPriceUsd($priceUsd) {
		$this->priceUsd = $priceUsd;
	}

	/**
	 * @return float
	 */
	public function getPriceUsdPerUnit(){
		return $this->priceUsdPerUnit;
	}

	/**
	 * @param float $priceUsdPerUnit
	 */
	public function setPriceUsdPerUnit($priceUsdPerUnit) {
		$this->priceUsdPerUnit = $priceUsdPerUnit;
	}

	/**
	 * @return float
	 */
	public function getPrice(){
		return $this->price;
	}

	/**
	 * @param float $price
	 */
	public function setPrice($price) {
		$this->price = $price;
	}

	/**
	 * @return float
	 */
	public function getPricePerUnit(){
		return $this->pricePerUnit;
	}

	/**
	 * @param float $pricePerUnit
	 */
	public function setPricePerUnit($pricePerUnit) {
		$this->pricePerUnit = $pricePerUnit;
	}

	/**
	 * @return bool
	 */
	public function isIsConditional(){
		return $this->isConditional;
	}

	/**
	 * @param bool $isConditional
	 */
	public function setIsConditional($isConditional) {
		$this->isConditional = $isConditional;
	}

	/**
	 * @return string
	 */
	public function getCondition(){
		return $this->condition;
	}

	/**
	 * @param string $condition
	 */
	public function setCondition($condition) {
		$this->condition = $condition;
	}

	/**
	 * @return float
	 */
	public function getConditionTarget() {
		return $this->conditionTarget;
	}

	/**
	 * @param float $conditionTarget
	 */
	public function setConditionTarget($conditionTarget) {
		$this->conditionTarget = $conditionTarget;
	}

	/**
	 * @return bool
	 */
	public function isImmediateOrCancel(){
		return $this->immediateOrCancel;
	}

	/**
	 * @param bool $immediateOrCancel
	 */
	public function setImmediateOrCancel($immediateOrCancel) {
		$this->immediateOrCancel = $immediateOrCancel;
	}

	/**
	 * @return \DateTime
	 */
	public function getClosedDate(){
		return $this->closedDate;
	}

	/**
	 * @param \DateTime $closedDate
	 */
	public function setClosedDate(\DateTime $closedDate) {
		$this->closedDate = $closedDate;
	}


}
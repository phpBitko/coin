<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Application\Sonata\UserBundle\Entity\User as User;

/**
 * Balances
 *
 * @ORM\Table(name="balances")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BalancesRepository")
 */
class Balances{
	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	public function __construct() {
		$this->addDate = new \DateTime(	'now', new \DateTimeZone('Europe/Kiev'));

	}

	/**
	 * @var text
	 *
	 * @ORM\Column(name="currency", type="text", nullable=true)
	 */
	private $currency;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=true)
	 */
	private $name;

	/**
	 * @return string
	 */
	public function getName(){
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name) {
		$this->name = $name;
	}


	/**
	 * @var float
	 *
	 * @ORM\Column(name="balance", type="float", nullable=true)
	 */
	private $balance;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="available", type="float", nullable=true)
	 */
	private $available;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="price_usd", type="float", nullable=true)
	 */
	private $priceUsd;

	/**
	 * @var User
	 *
	 * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User")
	 * @ORM\JoinColumn(name="id_users", referencedColumnName="id")
	 */
	private $idUsers;

	/**
	 * @return User
	 */
	public function getIdUsers(){
		return $this->idUsers;
	}

	/**
	 * @param User $idUsers
	 */
	public function setIdUsers(User $idUsers) {
		$this->idUsers = $idUsers;
	}


	/**
	 * @var float
	 *
	 * @ORM\Column(name="price_btc", type="float", nullable=true)
	 */
	private $priceBtc;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="profit", type="decimal", precision=10, scale=2, nullable=true)
	 */
	private $profit;

	/**
	 * @var text
	 *
	 * @ORM\Column(name="stock_exchange", type="text", nullable=true)
	 */
	private $stockExchange;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="is_active", type="boolean", nullable=true)
	 */
	private $isActive;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="my_balance", type="boolean", nullable=true)
	 */
	private $myBalance = false;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="farm1", type="integer", nullable=true)
	 * @Assert\Range(min = 0, max = 100)
	 */
	private $farm1 = 100;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="farm2", type="integer", nullable=true)
	 * @Assert\Range(min = 0, max = 100)
	 */
	private $farm2;

	/**
	 * @return float
	 */
	public function getPriceUsd(){
		return $this->priceUsd;
	}

	/**
	 * @param float*/
	public function setPriceUsd($priceUsd) {
		$this->priceUsd = $priceUsd;
	}

	/**
	 * @return float
	 */
	public function getPriceBtc(){
		return $this->priceBtc;
	}

	/**
	 * @param float
	 */
	public function setPriceBtc($priceBtc) {
		$this->priceBtc = $priceBtc;
	}

	/**
	 * @return int
	 */
	public function getFarm1() {
		return $this->farm1;
	}

	/**
	 * @param int $farm1
	 */
	public function setFarm1($farm1) {
		$this->farm1 = $farm1;
	}

	/**
	 * @return int
	 */
	public function getFarm2() {
		return $this->farm2;
	}

	/**
	 * @param int $farm2
	 */
	public function setFarm2($farm2) {
		$this->farm2 = $farm2;
	}


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
	 * @return bool
	 */
	public function isMyBalance() {
		return $this->myBalance;
	}

	/**
	 * @param bool $myBalance
	 */
	public function setMyBalance($myBalance) {
		$this->myBalance = $myBalance;
	}

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="add_date", type="datetime", nullable=true)
	 */
	private $addDate;

	/**
	 * @return \DateTime
	 */
	public function getAddDate() {
		return $this->addDate;
	}

	/**
	 * @param \DateTime $addDate
	 */
	public function setAddDate($addDate) {
		$this->addDate = $addDate;
	}


	/**
	 * @return bool
	 */
	public function isIsActive() {
		return $this->isActive;
	}

	/**
	 * @param bool $isActive
	 */
	public function setIsActive($isActive) {
		$this->isActive = $isActive;
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
	public function setStockExchange($stockExchange) {
		$this->stockExchange = $stockExchange;
	}


	/**
	 * @var float
	 *
	 * @ORM\Column(name="Pending", type="float", nullable=true)
	 */
	private $pending;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="crypto_address", type="text", nullable=true)
	 */
	private $cryptoAddress;
	/**
	 * @var float
	 *
	 * @ORM\Column(name="Requested", type="boolean", nullable=true)
	 */
	private $requested;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="Uuid", type="text", nullable=true)
	 */
	private $Uuid;

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
	public function getCurrency() {
		return $this->currency;
	}

	/**
	 * @param float $currency
	 */
	public function setCurrency($currency) {
		$this->currency = $currency;
	}

	/**
	 * @return float
	 */
	public function getBalance() {
		return $this->balance;
	}

	/**
	 * @param float $balance
	 */
	public function setBalance($balance) {
		$this->balance = $balance;
	}

	/**
	 * @return float
	 */
	public function getAvailable() {
		return $this->available;
	}

	/**
	 * @param float $available
	 */
	public function setAvailable($available) {
		$this->available = $available;
	}

	/**
	 * @return float
	 */
	public function getPending() {
		return $this->pending;
	}

	/**
	 * @param float $pending
	 */
	public function setPending($pending) {
		$this->pending = $pending;
	}

	/**
	 * @return float
	 */
	public function getCryptoAddress() {
		return $this->cryptoAddress;
	}

	/**
	 * @param float $cryptoAddress
	 */
	public function setCryptoAddress($cryptoAddress) {
		$this->cryptoAddress = $cryptoAddress;
	}

	/**
	 * @return float
	 */
	public function getRequested() {
		return $this->requested;
	}

	/**
	 * @param float $requested
	 */
	public function setRequested($requested) {
		$this->requested = $requested;
	}

	/**
	 * @return float
	 */
	public function getUuid() {
		return $this->Uuid;
	}

	/**
	 * @param float $Uuid
	 */
	public function setUuid($Uuid) {
		$this->Uuid = $Uuid;
	}




}
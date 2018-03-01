<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * DepositStatistic
 *
 * @ORM\Table(name="deposit_statistic")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DepositStatisticRepository")
 */
class DepositStatistic
{

	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;


	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="month", type="datetime", nullable=true)
	 */
	private $month;


	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="is_active", type="boolean")
	 */
	private $isActive = true;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="is_my_deposit", type="boolean")
	 */
	private $isMyDeposit;

	/**
	 * @var CryptoCurrency
	 *
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CryptoCurrency")
	 * @ORM\JoinColumn(name="id_crypto_currency", referencedColumnName="id")
	 */
	private $idCryptoCurrency;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="farm1", type="float", nullable=true)
	 */
	private $farm1;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="farm2", type="float", nullable=true)
	 */
	private $farm2;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="farm3", type="float", nullable=true)
	 */
	private $farm3;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="price_btc_perm", type="float", nullable=true)
	 */
	private $priceBtcPerm;


	/**
	 * @var float
	 *
	 * @ORM\Column(name="price_usd_perm", type="float", nullable=true)
	 */
	private $priceUsdPerm;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="price_btc_actual", type="float", nullable=true)
	 */
	private $priceBtcActual;


	/**
	 * @var float
	 *
	 * @ORM\Column(name="price_usd_actual", type="float", nullable=true)
	 */
	private $priceUsdActual;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="amount", type="float", nullable=true)
	 */
	private $amount;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="from_in", type="string", nullable=true)
	 */
	private $fromIn;

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId(int $id) {
		$this->id = $id;
	}

	/**
	 * @return CryptoCurrency
	 */
	public function getIdCryptoCurrency(): ?CryptoCurrency {
		return $this->idCryptoCurrency;
	}

	/**
	 * @param CryptoCurrency $idCryptoCurrency
	 */
	public function setIdCryptoCurrency(?CryptoCurrency $idCryptoCurrency) {
		$this->idCryptoCurrency = $idCryptoCurrency;
	}

	/**
	 * @return \DateTime
	 */
	public function getMonth(): ?\DateTime {
		return $this->month;
	}

	/**
	 * @param \DateTime $month
	 */
	public function setMonth(?\DateTime $month) {
		$this->month = $month;
	}

	/**
	 * @return bool
	 */
	public function isIsActive(): ?bool {
		return $this->isActive;
	}

	/**
	 * @param bool $isActive
	 */
	public function setIsActive(?bool $isActive) {
		$this->isActive = $isActive;
	}

	/**
	 * @return bool
	 */
	public function isIsMyDeposit(): ?bool {
		return $this->isMyDeposit;
	}

	/**
	 * @param bool $isMyDeposit
	 */
	public function setIsMyDeposit(?bool $isMyDeposit) {
		$this->isMyDeposit = $isMyDeposit;
	}

	/**
	 * @return float
	 */
	public function getFarm1(): ?float {
		return $this->farm1;
	}

	/**
	 * @param float $farm1
	 */
	public function setFarm1(?float $farm1) {
		$this->farm1 = $farm1;
	}

	/**
	 * @return float
	 */
	public function getFarm2(): ?float {
		return $this->farm2;
	}

	/**
	 * @param float $farm2
	 */
	public function setFarm2(?float $farm2) {
		$this->farm2 = $farm2;
	}

	/**
	 * @return float
	 */
	public function getFarm3(): ?float {
		return $this->farm3;
	}

	/**
	 * @param float $farm3
	 */
	public function setFarm3(?float $farm3) {
		$this->farm3 = $farm3;
	}

	/**
	 * @return float
	 */
	public function getPriceBtcPerm(): ?float {
		return $this->priceBtcPerm;
	}

	/**
	 * @param float $priceBtcPerm
	 */
	public function setPriceBtcPerm(?float $priceBtcPerm) {
		$this->priceBtcPerm = $priceBtcPerm;
	}

	/**
	 * @return float
	 */
	public function getPriceUsdPerm(): ?float {
		return $this->priceUsdPerm;
	}

	/**
	 * @param float $priceUsdPerm
	 */
	public function setPriceUsdPerm(?float $priceUsdPerm) {
		$this->priceUsdPerm = $priceUsdPerm;
	}

	/**
	 * @return float
	 */
	public function getPriceBtcActual(): ?float {
		return $this->priceBtcActual;
	}

	/**
	 * @param float $priceBtcActual
	 */
	public function setPriceBtcActual(?float $priceBtcActual) {
		$this->priceBtcActual = $priceBtcActual;
	}

	/**
	 * @return float
	 */
	public function getPriceUsdActual(): ?float {
		return $this->priceUsdActual;
	}

	/**
	 * @param float $priceUsdActual
	 */
	public function setPriceUsdActual(?float $priceUsdActual) {
		$this->priceUsdActual = $priceUsdActual;
	}

	/**
	 * @return float
	 */
	public function getAmount(): ?float {
		return $this->amount;
	}

	/**
	 * @param float $amount
	 */
	public function setAmount(?float $amount) {
		$this->amount = $amount;
	}

	/**
	 * @return string
	 */
	public function getFromIn(): ?string {
		return $this->fromIn;
	}

	/**
	 * @param string $fromIn
	 */
	public function setFromIn(?string $fromIn) {
		$this->fromIn = $fromIn;
	}

}
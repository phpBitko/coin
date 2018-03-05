<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Deposit
 *
 * @ORM\Table(name="deposit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DepositRepository")
 */
class Deposit
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
	 * @ORM\Column(name="add_date", type="datetime", nullable=true)
	 */
	private $addDate;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="get_date", type="datetime", nullable=true)
	 */
	private $getDate;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="currency", type="string", nullable=true)
	 */
	private $currency;

	/**
	 * @var text
	 *
	 * @ORM\Column(name="tx_id", type="string", nullable=true)
	 */
	private $txId;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="is_active", type="boolean")
	 */
	private $isActive = true;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="from_in", type="string", nullable=true)
	 */
	private $fromIn;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="crypto_address", type="string", nullable=true)
	 */
	private $cryptoAddress;


	/**
	 * @var float
	 *
	 * @ORM\Column(name="id_api", type="float", nullable=true)
	 */
	private $idApi;


	/**
	 * @var float
	 *
	 * @ORM\Column(name="amount", type="float", nullable=true)
	 */
	private $amount;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="confirmations", type="float", nullable=true)
	 */
	private $confirmations;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="currency_name", type="string", nullable=true)
	 */
	private $currencyName;


	public function __construct() {
		$this->addDate = new \DateTime('now', new \DateTimeZone('Europe/Kiev'));
	}

	/**
	 * @return bool
	 */
	public function isIsActive(): bool {
		return $this->isActive;
	}

	/**
	 * @param bool $isActive
	 */
	public function setIsActive(bool $isActive) {
		$this->isActive = $isActive;
	}

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getFromIn(): string {
		return $this->fromIn;
	}

	/**
	 * @param string $fromIn
	 */
	public function setFromIn(string $fromIn) {
		$this->fromIn = $fromIn;
	}

	/**
	 * @param int $id
	 */
	public function setId(int $id) {
		$this->id = $id;
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
	 * @return \DateTime
	 */
	public function getGetDate(): \DateTime {
		return $this->getDate;
	}

	/**
	 * @param \DateTime $getDate
	 */
	public function setGetDate(\DateTime $getDate) {
		$this->getDate = $getDate;
	}

	/**
	 * @return string
	 */
	public function getCurrency(): string {
		return $this->currency;
	}

	/**
	 * @param string $currency
	 */
	public function setCurrency(string $currency) {
		$this->currency = $currency;
	}

	/**
	 * @return string
	 */
	public function getTxId(): string {
		return $this->txId;
	}

	/**
	 * @param string $txId
	 */
	public function setTxId(string $txId) {
		$this->txId = $txId;
	}

	/**
	 * @return string
	 */
	public function getCryptoAddress(): ?string {
		return $this->cryptoAddress;
	}

	/**
	 * @param string $cryptoAddress
	 */
	public function setCryptoAddress(?string $cryptoAddress) {
		$this->cryptoAddress = $cryptoAddress;
	}

	/**
	 * @return float
	 */
	public function getIdApi(): float {
		return $this->idApi;
	}

	/**
	 * @param float $idApi
	 */
	public function setIdApi(float $idApi) {
		$this->idApi = $idApi;
	}

	/**
	 * @return float
	 */
	public function getAmount(): float {
		return $this->amount;
	}

	/**
	 * @param float $amount
	 */
	public function setAmount(float $amount) {
		$this->amount = $amount;
	}

	/**
	 * @return float
	 */
	public function getConfirmations(): float {
		return $this->confirmations;
	}

	/**
	 * @param float $confirmations
	 */
	public function setConfirmations(float $confirmations) {
		$this->confirmations = $confirmations;
	}

	/**
	 * @return string
	 */
	public function getCurrencyName(): string {
		return $this->currencyName;
	}

	/**
	 * @param string $currencyName
	 */
	public function setCurrencyName(string $currencyName) {
		$this->currencyName = $currencyName;
	}
}
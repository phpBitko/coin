<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CryptoCurrency
 *
 * @ORM\Table(name="crypto_currency")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CryptoCurrencyRepository")
 */
class CryptoCurrency
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=500)
     */
    private $name;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="volume_usd_24h", type="float", nullable=true)
	 */
	private $volumeUsd24h;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="available_supply", type="float", nullable=true)
	 */
	private $availableSupply;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="id_api", type="string", length=500, nullable=true)
	 */
	private $idApi;


	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="last_updated", type="datetime", nullable=true)
	 */
	private $lastUpdated;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="market_cap_usd", type="float", nullable=true)
	 */
	private $marketCapUsd;


	/**
	 * @var float
	 *
	 * @ORM\Column(name="max_supply", type="float", nullable=true)
	 */
	private $maxSupply;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="percent_change_1h", type="float", nullable=true)
	 */
	private $percentChange1h;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="percent_change_7d", type="float", nullable=true)
	 */
	private $percentChange7d;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="percent_change_24h", type="float", nullable=true)
	 */
	private $percentChange24h;


	/**
	 * @var float
	 *
	 * @ORM\Column(name="price_btc", type="float", nullable=true)
	 */
	private $priceBtc;


	/**
	 * @var float
	 *
	 * @ORM\Column(name="price_usd", type="float", nullable=true)
	 */
	private $priceUsd;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="rank", type="integer", nullable=true)
	 */
	private $rank;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="symbol", type="string", length=500, nullable=true)
	 */
	private $symbol;

	/**
	 * @return float
	 */
	public function getVolumeUsd24h() {
		return $this->volumeUsd24h;
	}

	/**
	 * @param float $volumeUsd24h
	 */
	public function setVolumeUsd24h($volumeUsd24h) {
		$this->volumeUsd24h = $volumeUsd24h;
	}

	/**
	 * @return float
	 */
	public function getAvailableSupply() {
		return $this->availableSupply;
	}

	/**
	 * @param float $availableSupply
	 */
	public function setAvailableSupply($availableSupply) {
		$this->availableSupply = $availableSupply;
	}

	/**
	 * @return string
	 */
	public function getIdApi() {
		return $this->idApi;
	}

	/**
	 * @param string $idApi
	 */
	public function setIdApi($idApi) {
		$this->idApi = $idApi;
	}

	/**
	 * @return \DateTime
	 */
	public function getLastUpdated() {
		return $this->lastUpdated;
	}

	/**
	 * @param \DateTime $lastUpdated
	 */
	public function setLastUpdated($lastUpdated) {
		$this->lastUpdated = $lastUpdated;
	}

	/**
	 * @return float
	 */
	public function getMarketCapUsd() {
		return $this->marketCapUsd;
	}

	/**
	 * @param float $marketCapUsd
	 */
	public function setMarketCapUsd($marketCapUsd) {
		$this->marketCapUsd = $marketCapUsd;
	}

	/**
	 * @return float
	 */
	public function getMaxSupply() {
		return $this->maxSupply;
	}

	/**
	 * @param float $maxSupply
	 */
	public function setMaxSupply($maxSupply) {
		$this->maxSupply = $maxSupply;
	}

	/**
	 * @return float
	 */
	public function getPercentChange1h() {
		return $this->percentChange1h;
	}

	/**
	 * @param float $percentChange1h
	 */
	public function setPercentChange1h($percentChange1h) {
		$this->percentChange1h = $percentChange1h;
	}

	/**
	 * @return float
	 */
	public function getPercentChange7d() {
		return $this->percentChange7d;
	}

	/**
	 * @param float $percentChange7d
	 */
	public function setPercentChange7d($percentChange7d) {
		$this->percentChange7d = $percentChange7d;
	}

	/**
	 * @return float
	 */
	public function getPercentChange24h() {
		return $this->percentChange24h;
	}

	/**
	 * @param float $percentChange24h
	 */
	public function setPercentChange24h($percentChange24h) {
		$this->percentChange24h = $percentChange24h;
	}

	/**
	 * @return float
	 */
	public function getPriceBtc() {
		return $this->priceBtc;
	}

	/**
	 * @param float $priceBtc
	 */
	public function setPriceBtc($priceBtc) {
		$this->priceBtc = $priceBtc;
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
	public function setPriceUsd($priceUsd) {
		$this->priceUsd = $priceUsd;
	}

	/**
	 * @return int
	 */
	public function getRank() {
		return $this->rank;
	}

	/**
	 * @param int $rank
	 */
	public function setRank($rank) {
		$this->rank = $rank;
	}

	/**
	 * @return string
	 */
	public function getSymbol() {
		return $this->symbol;
	}

	/**
	 * @param string $symbol
	 */
	public function setSymbol($symbol) {
		$this->symbol = $symbol;
	}

	/**
	 * @return float
	 */
	public function getTotalSupply() {
		return $this->totalSupply;
	}

	/**
	 * @param float $totalSupply
	 */
	public function setTotalSupply($totalSupply) {
		$this->totalSupply = $totalSupply;
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
	 * @var float
	 *
	 * @ORM\Column(name="total_supply", type="float", nullable=true)
	 */
	private $totalSupply;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="is_active", type="boolean", nullable=true)
	 */
	private $isActive;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="is_my_currency", type="boolean", nullable=true)
	 */
	private $isMyCurrency;

	/**
	 * @return bool
	 */
	public function isIsMyCurrency(){
		return $this->isMyCurrency;
	}

	/**
	 * @param bool $isMyCurrency
	 */
	public function setIsMyCurrency(bool $isMyCurrency) {
		$this->isMyCurrency = $isMyCurrency;
	}

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return cryptoCurrency
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}


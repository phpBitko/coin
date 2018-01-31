<?php
namespace AppBundle\Entity;

use AppBundle\Service\Currency;
use Doctrine\ORM\Mapping as ORM;

/**
* Mining
*
* @ORM\Table(name="mining")
* @ORM\Entity(repositoryClass="AppBundle\Repository\MiningRepository")
*/
class Mining{

	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	public function __construct() {
		//$this->addDate = new \DateTime(	'now', new \DateTimeZone('Europe/Kiev'));

	}

	/**
	 * @var int
	 *
	 * @ORM\ManyToOne(targetEntity="CryptoCurrency", inversedBy="mining")
	 * @ORM\JoinColumn(name="id_currency", referencedColumnName="id")
	 */
	private $idCurrency;

	/**
	 * @var int
	 *
	 * @ORM\ManyToOne(targetEntity="Farm", inversedBy="mining")
	 * @ORM\JoinColumn(name="id_farm", referencedColumnName="id")
	 */
	private $idFarm;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="start_date", type="datetime", nullable=true)
	 */
	private $startDate;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="end_date", type="datetime", nullable=true)
	 */
	private $endDate;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="start_balance", type="float", nullable=true)
	 */
	private $startBalance;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="end_balance", type="float", nullable=true)
	 */
	private $endBalance;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="difference_balance", type="float", nullable=true)
	 */
	private $differenceBalance;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="difference_date", type="float", nullable=true)
	 */
	private $differenceDate;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="num_card", type="float", nullable=true)
	 */
	private $numCard;



	/**
	 * @var float
	 *
	 * @ORM\Column(name="difference_balance_usd", type="float", nullable=true)
	 */
	private $differenceBalanceUsd;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="profit_usd_per_day", type="float", nullable=true)
	 */
	private $profitUsdPerDay;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="profit_usd_per_day_on_card", type="float", nullable=true)
	 */
	private $profitUsdPerDayOnCard;


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
	public function getIdCurrency() {
		return $this->idCurrency;
	}

	/**
	 * @param CryptoCurrency $idCurrency
	 */
	public function setIdCurrency(CryptoCurrency $idCurrency) {
		$this->idCurrency = $idCurrency;
	}

	/**
	 * @return Farm
	 */
	public function getIdFarm(){
		return $this->idFarm;
	}

	/**
	 * @param Farm $idFarm
	 */
	public function setIdFarm(Farm $idFarm) {
		$this->idFarm = $idFarm;
	}

	/**
	 * @return float
	 */
	public function getProfitUsdPerDayOnCard(){
		return $this->profitUsdPerDayOnCard;
	}

	/**
	 * @param float $profitUsdPerDayOnCard
	 */
	public function setProfitUsdPerDayOnCard(float $profitUsdPerDayOnCard) {
		$this->profitUsdPerDayOnCard = $profitUsdPerDayOnCard;
	}

	/**
	 * @return \DateTime
	 */
	public function getStartDate(){
		return $this->startDate;
	}

	/**
	 * @param \DateTime $startDate
	 */
	public function setStartDate($startDate) {
		$this->startDate = $startDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getEndDate(){
		return $this->endDate;
	}

	/**
	 * @param \DateTime $endDate
	 */
	public function setEndDate($endDate) {
		$this->endDate = $endDate;
	}

	/**
	 * @return float
	 */
	public function getStartBalance(){
		return $this->startBalance;
	}

	/**
	 * @param float $startBalance
	 */
	public function setStartBalance(float $startBalance) {
		$this->startBalance = $startBalance;
	}

	/**
	 * @return float
	 */
	public function getEndBalance(){
		return $this->endBalance;
	}

	/**
	 * @param float $endBalance
	 */
	public function setEndBalance(float $endBalance) {
		$this->endBalance = $endBalance;
	}

	/**
	 * @return float
	 */
	public function getDifferenceBalance(){
		return $this->differenceBalance;
	}

	/**
	 * @param float $differenceBalance
	 */
	public function setDifferenceBalance(float $differenceBalance) {
		$this->differenceBalance = $differenceBalance;
	}

	/**
	 * @return float
	 */
	public function getDifferenceDate(){
		return $this->differenceDate;
	}

	/**
	 * @param float $differenceDate
	 */
	public function setDifferenceDate($differenceDate) {
		$this->differenceDate = $differenceDate;
	}

	/**
	 * @return float
	 */
	public function getDifferenceBalanceUsd(){
		return $this->differenceBalanceUsd;
	}

	/**
	 * @param float $differenceBalanceUsd
	 */
	public function setDifferenceBalanceUsd(float $differenceBalanceUsd) {
		$this->differenceBalanceUsd = $differenceBalanceUsd;
	}

	/**
	 * @return float
	 */
	public function getProfitUsdPerDay(){
		return $this->profitUsdPerDay;
	}

	/**
	 * @param float $profitUsdPerDay
	 */
	public function setProfitUsdPerDay(float $profitUsdPerDay) {
		$this->profitUsdPerDay = $profitUsdPerDay;
	}

	/**
	 * @return float
	 */
	public function getNumCard() {
		return $this->numCard;
	}

	/**
	 * @param float $numCard
	 */
	public function setNumCard(float $numCard) {
		$this->numCard = $numCard;
	}



}
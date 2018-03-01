<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * DepositStatistic
 *
 * @ORM\Table(name="deposit_month")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DepositMonthRepository")
 */
class DepositMonth
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
	 * @var float
	 *
	 * @ORM\Column(name="farm1_sum", type="float", nullable=true)
	 */
	private $farm1Sum;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="farm2_sum", type="float", nullable=true)
	 */
	private $farm2Sum;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="farm3_sum", type="float", nullable=true)
	 */
	private $farm3Sum;


	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="is_active", type="boolean")
	 */
	private $isActive = true;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="price_usd_perm", type="float", nullable=true)
	 */
	private $priceUsdPerm;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="price_usd_actual", type="float", nullable=true)
	 */
	private $priceUsdActual;

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
	 * @return \DateTime
	 */
	public function getMonth(): \DateTime {
		return $this->month;
	}

	/**
	 * @param \DateTime $month
	 */
	public function setMonth(\DateTime $month) {
		$this->month = $month;
	}

	/**
	 * @return float
	 */
	public function getFarm1Sum(): ?float {
		return $this->farm1Sum;
	}

	/**
	 * @param float $farm1Sum
	 */
	public function setFarm1Sum(?float $farm1Sum) {
		$this->farm1Sum = $farm1Sum;
	}

	/**
	 * @return float
	 */
	public function getFarm2Sum(): ?float {
		return $this->farm2Sum;
	}

	/**
	 * @param float $farm2Sum
	 */
	public function setFarm2Sum(?float $farm2Sum) {
		$this->farm2Sum = $farm2Sum;
	}

	/**
	 * @return float
	 */
	public function getFarm3Sum(): ?float {
		return $this->farm3Sum;
	}

	/**
	 * @param float $farm3Sum
	 */
	public function setFarm3Sum(?float $farm3Sum) {
		$this->farm3Sum = $farm3Sum;
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




}
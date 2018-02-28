<?php

namespace AppBundle\Service;

use AppBundle\Entity\CryptoCurrency;
use AppBundle\Entity\Balances;
use AppBundle\Entity\Deposit;
use AppBundle\Entity\DepositStatistic;
use AppBundle\Entity\OrderHistory;
use AppBundle\Repository\cryptoCurrencyRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Acl\Exception\Exception;

class DepositServices
{
	public $entityManager;

	public function __construct(EntityManagerInterface $entityManager) {
		$this->entityManager = $entityManager;
	}


	public function addBittrexDeposit(array $deposits) {
		$em = $this->entityManager;
		foreach ($deposits as $deposit) {
			$cryptoCurrency = $em->getRepository('AppBundle:Deposit')->findOneBy(array('idApi' => $deposit['Id']));
			if (empty($cryptoCurrency)) {
				$depositEntity = new Deposit();
				$depositEntity->setFromIn('Bittrex');
				$depositEntity->setIdApi($deposit['Id']);
				$depositEntity->setCurrency($deposit['Currency']);
				$cryptoCurrency =
					$em->getRepository('AppBundle:CryptoCurrency')->findOneBy(array('symbol' => $deposit['Currency']));
				$depositEntity->setCurrencyName($cryptoCurrency->getName());
				$depositEntity->setAmount($deposit['Amount']);
				$depositEntity->setConfirmations($deposit['Confirmations']);
				$depositEntity->setTxId($deposit['TxId']);
				$depositEntity->setCryptoAddress($deposit['CryptoAddress']);
				$getDate = new \DateTime($deposit['LastUpdated']);
				$getDate->setTimezone(new \DateTimeZone('Europe/Kiev'));
				$depositEntity->setGetDate($getDate);
				$em->persist($depositEntity);
			}
		}

		return true;
	}

	public function addDepositStatistic(array $groupDeposit) {
		$em = $this->entityManager;
		foreach ($groupDeposit as $deposit) {
			$cryptoCurrency =
				$em->getRepository('AppBundle:CryptoCurrency')->findOneBy(array('symbol' => $deposit['currency']));
			$depositStatistic = $em->getRepository('AppBundle:DepositStatistic')->findOneBy(array(
						'fromIn' => $deposit['from_in'],
						'idCryptoCurrency' => $cryptoCurrency->getId(),
						'month' => $getDate = new \DateTime($deposit['month']),
						'isActive' => true,
					));

			if (empty($depositStatistic)) {
				$depositStatistic = new DepositStatistic();
				$depositStatistic->setFromIn($deposit['from_in']);
				$month = new \DateTime($deposit['month']);
				$depositStatistic->setMonth($month);
				$depositStatistic->setIsMyDeposit(false);
				$depositStatistic->setIdCryptoCurrency($cryptoCurrency);
			}
			$depositStatistic->setAmount(round($deposit['sum_amount'], 4));
			$depositStatistic->setPriceBtcActual(round($cryptoCurrency->getPriceBtc() * $deposit['sum_amount'], 6));
			$depositStatistic->setPriceUsdActual(round($cryptoCurrency->getPriceUsd() * $deposit['sum_amount'], 2));
			$dateNow = new \DateTime();
			if (substr($deposit['month'], 0, 7) == $dateNow->format('Y-m')) {
				$depositStatistic->setPriceBtcPerm(round($cryptoCurrency->getPriceBtc() * $deposit['sum_amount'], 6));
				$depositStatistic->setPriceUsdPerm(round($cryptoCurrency->getPriceUsd() * $deposit['sum_amount'], 2));
			}
			$em->persist($depositStatistic);
		}

		return true;

	}

	public function updateMyDepositStatistic(array $myDepositStatistics) {
		$em = $this->entityManager;
		foreach ($myDepositStatistics as $myDeposit) {
			$cryptoCurrency = $em->getRepository('AppBundle:CryptoCurrency')->find($myDeposit->getIdCryptoCurrency());
			$myDeposit->setPriceBtcActual(round($cryptoCurrency->getPriceBtc() * $myDeposit->getAmount(), 6));
			$myDeposit->setPriceUsdActual(round($cryptoCurrency->getPriceUsd() * $myDeposit->getAmount(), 2));
			$dateNow = new \DateTime();
			if (substr($myDeposit->getMonth()->format('Y-m'), 0, 7) == $dateNow->format('Y-m')) {
				$myDeposit->setPriceBtcPerm(round($cryptoCurrency->getPriceBtc() * $myDeposit->getAmount(), 6));
				$myDeposit->setPriceUsdPerm(round($cryptoCurrency->getPriceUsd() * $myDeposit->getAmount(), 2));
			}
			$em->persist($myDeposit);
		}
	}


}
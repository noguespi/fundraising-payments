<?php

declare( strict_types = 1 );

namespace WMDE\Fundraising\PaymentContext\Domain\Model;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class DirectDebitPayment implements PaymentMethod {

	private $bankData;

	public function __construct( BankData $bankData ) {
		$this->bankData = $bankData;
	}

	public function getId(): string {
		return PaymentMethod::DIRECT_DEBIT;
	}

	public function getBankData(): BankData {
		return $this->bankData;
	}

}
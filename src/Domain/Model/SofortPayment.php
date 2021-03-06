<?php

declare( strict_types = 1 );

namespace WMDE\Fundraising\PaymentContext\Domain\Model;

use DateTime;

class SofortPayment implements PaymentMethod {

	/**
	 * @var string
	 */
	private $bankTransferCode = '';
	/**
	 * @var DateTime|null
	 */
	private $confirmedAt;

	public function __construct( string $bankTransferCode ) {
		$this->bankTransferCode = $bankTransferCode;
	}

	public function getId(): string {
		return PaymentMethod::SOFORT;
	}

	public function getBankTransferCode(): string {
		return $this->bankTransferCode;
	}

	public function getConfirmedAt(): ?DateTime {
		return $this->confirmedAt;
	}

	public function setConfirmedAt( ?DateTime $confirmedAt ): void {
		$this->confirmedAt = $confirmedAt;
	}

	public function isConfirmedPayment(): bool {
		return !is_null( $this->getConfirmedAt() );
	}
}

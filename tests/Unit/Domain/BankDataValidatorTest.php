<?php

declare( strict_types = 1 );

namespace WMDE\Fundraising\Frontend\PaymentContext\Tests\Unit\Domain;

use WMDE\Fundraising\Frontend\PaymentContext\Domain\BankDataValidator;
use WMDE\Fundraising\Frontend\PaymentContext\Domain\IbanValidator;
use WMDE\Fundraising\Frontend\PaymentContext\Domain\Model\BankData;
use WMDE\Fundraising\Frontend\PaymentContext\Domain\Model\Iban;
use WMDE\Fundraising\Frontend\Tests\Unit\Validation\ValidatorTestCase;
use WMDE\FunValidators\ValidationResult;

/**
 * @covers \WMDE\Fundraising\Frontend\PaymentContext\Domain\BankDataValidator
 *
 * @license GNU GPL v2+
 * @author Kai Nissen < kai.nissen@wikimedia.de >
 */
class BankDataValidatorTest extends ValidatorTestCase {

	/**
	 * @dataProvider invalidBankDataProvider
	 */
	public function testFieldsMissing_validationFails( string $iban, string $bic, string $bankName,
		string $bankCode, string $account ): void {

		$bankDataValidator = $this->newBankDataValidator();
		$bankData = $this->newBankData( $iban, $bic, $bankName, $bankCode, $account );
		$this->assertFalse( $bankDataValidator->validate( $bankData )->isSuccessful() );
	}

	public function invalidBankDataProvider(): array {
		return [
			[
				'DB00123456789012345678',
				'',
				'',
				'',
				'',
			],
			[
				'',
				'SCROUSDBXXX',
				'',
				'',
				'',
			],
			[
				'',
				'',
				'Scrooge Bank',
				'',
				'',
			],
			# validation fails for German IBAN and missing obsolete account data
			[
				'DE00123456789012345678',
				'SCROUSDBXXX',
				'Scrooge Bank',
				'',
				'',
			],
		];
	}

	public function testAllRequiredFieldsGiven_validationSucceeds(): void {
		$bankDataValidator = $this->newBankDataValidator();
		$bankData = $this->newBankData(
			'DE00123456789012345678',
			'SCROUSDBXXX',
			'Scrooge Bank',
			'12345678',
			'1234567890'
		);
		$this->assertTrue( $bankDataValidator->validate( $bankData )->isSuccessful() );
	}

	public function validBankDataProvider(): array {
		return [
			[
				'DB00123456789012345678',
				'SCROUSDBXXX',
				'Scrooge Bank',
				'',
				'',
			],
			[
				'DE00123456789012345678',
				'SCROUSDBXXX',
				'Scrooge Bank',
				'12345678',
				'1234567890',
			],
		];
	}

	private function newBankData( string $iban, string $bic, string $bankName, string $bankCode, string $account ): BankData {
		return ( new BankData() )
			->setIban( new Iban( $iban ) )
			->setBic( $bic )
			->setBankName( $bankName )
			->setBankCode( $bankCode )
			->setAccount( $account );
	}

	private function newBankDataValidator(): BankDataValidator {
		$ibanValidatorMock = $this->getMockBuilder( IbanValidator::class )->disableOriginalConstructor()->getMock();
		$ibanValidatorMock->method( 'validate' )
			->willReturn( new ValidationResult() );

		return new BankDataValidator( $ibanValidatorMock );
	}
}

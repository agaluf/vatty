<?php

namespace Vatty\tests\Unit\Entity;

use PHPUnit\Framework\TestCase;

use Vatty\Entity\Vat;

/**
 * @coversDefaultClass \Vatty\Entity\Vat
 */
class VatTest extends TestCase {

  /**
   * Assert that the Vat object is correctly constructed.
   *
   * @covers ::__construct
   */
  public function testConstructor() {
    $country = 'TE';
    $regEx = ['foo'];
    $vat = new Vat($country, $regEx);

    $setCountry = $this->readAttribute($vat, 'countryCode');
    $this->assertSame($country, $setCountry);

    $setRegEx = $this->readAttribute($vat, 'regEx');
    $this->assertSame($regEx, $setRegEx);
  }

  /**
   * Assert that Vat validation is correct.
   *
   * @covers ::validate
   * @dataProvider provideValidationData
   */
  public function testValidation($country, $regEx, $code, $expectedResult) {
    $vat = new Vat($country, $regEx);

    $this->assertSame($expectedResult, $vat->validate($code));
  }

  /**
   * Provides validation test data.
   *
   * @return array
   */
  public function provideValidationData() {
    return [
      ['DE', ['DE\d{9}'], 'DE123456789', TRUE],
      ['DE', ['DE\d{9}'], 'DE12345', FALSE],
      ['DE', ['DE\d{9}'], 'DE1234567890', FALSE]
    ];
  }

  /**
   * Assert that the country getter is correct.
   *
   * @covers ::getCountryCode
   */
  public function testCountryCode() {
    $country = 'ST';
    $regEx = [];

    $vat = new Vat($country, $regEx);
    $this->assertSame($country, $vat->getCountryCode());
  }

  /**
   * Assert that the regex getter is correct.
   *
   * @covers ::getRegEx
   */
  public function testRegEx() {
    $country = 'FO';
    $regEx = ['bar'];

    $vat = new Vat($country, $regEx);
    $this->assertSame($regEx, $vat->getRegEx());
  }

}
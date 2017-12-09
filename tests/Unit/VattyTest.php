<?php

namespace Vatty\tests\Unit;

use PHPUnit\Framework\TestCase;
use Vatty\Entity\Vat;
use Vatty\Exception\UnknownCountryException;
use Vatty\Repository\VatRepositoryInterface;
use Vatty\Vatty;

/**
 * @coversDefaultClass \Vatty\Vatty
 */
class VattyTest extends TestCase {

  /**
   * Assert that Vatty is correct constructed.
   *
   * @covers ::__construct
   */
  public function testConstruct() {
    $repository = $this->getMockBuilder('\Vatty\Repository\VatRepository')
      ->disableOriginalConstructor()
      ->getMock();

    $vatty = new Vatty($repository);

    $setRepo = $this->readAttribute($vatty, 'repository');

    $this->assertTrue($setRepo instanceOf VatRepositoryInterface);
  }

  /**
   * Assert that Vatty falls back to standard Vat Repository if no repository is
   * passed to the constructor.
   *
   * @covers ::__construct
   */
  public function testDefaultConstruct() {
    $vatty = new Vatty();
    $setRepo = $this->readAttribute($vatty, 'repository');
    $this->assertTrue($setRepo instanceOf VatRepositoryInterface);
  }

  /**
   * Test simple validation.
   *
   * @covers ::validate
   * @dataProvider provideValidationData
   */
  public function testValidation($countryCode, $vat, $expectedResult) {
    $repository = $this->getMockBuilder('\Vatty\Repository\VatRepository')
      ->disableOriginalConstructor()
      ->getMock();

    $mockVat = new Vat($countryCode, ['DE[0-9]{9}']);

    $repository->method('get')->willReturn($mockVat);

    $vatty = new Vatty($repository);
    $result = $vatty->validate($countryCode, $vat);
    $this->assertSame($expectedResult, $result->isValid());
  }

  /**
   * Test that invalid data will fail validation.
   *
   * @covers ::validate
   */
  public function testValidationException() {
    $repository = $this->getMockBuilder('\Vatty\Repository\VatRepository')
      ->disableOriginalConstructor()
      ->getMock();

    $repository->method('get')->willThrowException(new UnknownCountryException());

    $vatty = new Vatty($repository);
    $result = $vatty->validate('Foo', 'Bar');

    $this->assertSame(FALSE, $result->isValid());
  }

  /**
   * Provides data to test validation.
   *
   * @return array
   */
  public function provideValidationData() {
    return [
      ['DE', 'DE123456789', TRUE],
      ['DE', 'FR123456789', FALSE],
      ['DE', 'DE12345678', FALSE],
      ['DE', 'DE1234567890', FALSE],
      ['FO', 'Invalid', FALSE],
    ];
  }

}
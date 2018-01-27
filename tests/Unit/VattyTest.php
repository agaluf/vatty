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

  public function getViesServiceMock() {
    return $this->getMockBuilder('\Vatty\Vies\ViesService')
      ->disableOriginalConstructor()
      ->getMock();
  }

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
   * Assert that setting a requester is correctly performed.
   *
   * @covers ::setRequester
   */
  public function testSetRequester() {
    $vatty = new Vatty();

    $vatty->setRequester('Foo', 'Bar');

    $country = $this->readAttribute($vatty, 'requesterCountry');
    $vat = $this->readAttribute($vatty, 'requesterVat');

    $this->assertSame('Foo', $country);
    $this->assertSame('Bar', $vat);
  }

  /**
   * Assert that requester is correctly detected as empty.
   *
   * @covers ::requesterEmpty
   * @dataProvider provideRequesterData
   */
  public function testRequesterEmpty($country, $vat, $expectedResult) {
    $vatty = new Vatty();
    $vatty->setRequester($country, $vat);

    $this->assertSame($expectedResult, $vatty->requesterEmpty());
  }

  /**
   * Assert that Vies is correctly set.
   *
   * @covers ::useVies
   */
  public function testUseVies() {
    $vatty = new Vatty();
    $vatty->useVies();

    $result = $this->readAttribute($vatty, 'vies');

    $this->assertTrue($result);
  }

  /**
   * Assert that cache is correctly disabled.
   *
   * @covers ::disableCache
   */
  public function testDisableCache() {
    $vatty = new Vatty();
    $vatty->disableCache();

    $cache = $this->readAttribute($vatty, 'useCache');

    $this->assertFalse($cache);
  }

  /**
   * Test Vies simple validation.
   *
   * @covers ::validate
   * @covers ::performViesValidation
   */
  public function testViesSimpleValidation() {
    $service = $this->getViesServiceMock();

    $service->method('simpleValidation')->willReturn('simpleValidation');
    $service->method('qualifiedValidation')->willReturn('qualifiedValidation');

    $vatty = new Vatty(NULL, $service);
    $vatty->useVies();

    $this->assertSame('simpleValidation', $vatty->validate('DE', 'DE123456789'));
  }

  /**
   * Test vies qualified validation.
   *
   * @covers ::validate
   * @covers ::performViesValidation
   */
  public function testViesQualifiedValidation() {
    $service = $this->getViesServiceMock();

    $service->method('simpleValidation')->willReturn('simpleValidation');
    $service->method('qualifiedValidation')->willReturn('qualifiedValidation');

    $vatty = new Vatty(NULL, $service);
    $vatty->useVies();
    $vatty->setRequester('DE', 'DE123456789');

    $this->assertSame('qualifiedValidation', $vatty->validate('DE', 'DE123456789'));
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
      ['DE', 'DE 123456789', TRUE],
      ['DE', 'DE12345678', FALSE],
      ['DE', 'DE1234567890', FALSE],
      ['FO', 'Invalid', FALSE],
    ];
  }

  /**
   * Provides data to test requester info.
   *
   * @return array
   */
  public function provideRequesterData() {
    return [
      ['DE', 'DE123456789', FALSE],
      [NULL, NULL, TRUE],
      ['DE', NULL, TRUE],
      [NULL, 'DE123456789', TRUE],
    ];
  }


}
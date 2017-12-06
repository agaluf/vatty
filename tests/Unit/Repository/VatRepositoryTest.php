<?php

namespace Vatty\tests\Unit\Repository;

use PHPUnit\Framework\TestCase;
use Vatty\Repository\VatRepository;
use Vatty\VatInterface;

/**
 * @coversDefaultClass \Vatty\Repository\VatRepository
 */
class VatRepositoryTest extends TestCase {

  /**
   * Assert that the object is correctly constructed.
   *
   * @covers ::__construct
   */
  public function testConstruct() {
    $definitions = [
      'FO' => ['Bar']
    ];

    $repository = new VatRepository($definitions);
    $setProperty = $this->readAttribute($repository, 'definitions');

    $this->assertSame($definitions, $setProperty);
  }

  /**
   * Assert that get works correctly.
   *
   * @covers ::get
   */
  public function testGet() {
    $definitions = [
      'FO' => ['Bar']
    ];

    $repository = new VatRepository($definitions);
    $this->assertTrue($repository->get('FO') instanceOf VatInterface);
  }

  /**
   * Assert that get throws an exception if country code is unknown.
   *
   * @covers ::get
   * @expectedException \Vatty\Exception\UnknownCountryException
   */
  public function testGetException() {
    $definitions = [
      'FO' => ['Bar']
    ];

    $repository = new VatRepository($definitions);
    $repository->get('BA');
  }

  /**
   * Assert that getAll correctly returns all definitions.
   */
  public function testGetAll() {
    $definitions = [
      'FO' => ['Bar'],
      'BA' => ['Baz'],
    ];

    $repository = new VatRepository($definitions);
    $all = $repository->getAll();

    $this->assertSame(2, count($all));
  }

  /**
   * Validate default data with VIES rules and examples
   *
   * @dataProvider provideViesSamples
   *
   * @see http://ec.europa.eu/taxation_customs/vies/faq.html
   */
  public function testDefaultData($countryCode, $sample) {
    $repository = new VatRepository();
    $vat = $repository->get($countryCode);

    $this->assertTrue($vat->validate($sample));
  }

  /**
   * Provides VIES samples for default data tests.
   *
   * @return array
   */
  public function provideViesSamples() {
    return [
      ['AT', 'ATU99999999'],
      ['BE', 'BE0999999999'],
      ['BG', 'BG999999999'],
      ['BG', 'BG9999999999'],
      ['CY', 'CY99999999L'],
      ['CZ', 'CZ99999999'],
      ['CZ', 'CZ999999999'],
      ['CZ', 'CZ9999999999'],
      ['DE', 'DE999999999'],
      ['DK', 'DK99 99 99 99'],
      ['EE', 'EE999999999'],
      ['GR', 'EL999999999'],
      ['ES', 'ESX99999999'],
      ['ES', 'ESX9999999X'],
      ['ES', 'ES99999999X'],
      ['FI', 'FI99999999'],
      ['FR', 'FRXX 999999999'],
      ['GB', 'GB999 9999 99'],
      ['GB', 'GB999 9999 99 999'],
      ['GB', 'GBGD999'],
      ['GB', 'GBHA999'],
      ['HR', 'HR99999999999'],
      ['HU', 'HU99999999'],
      ['IE', 'IE9S99999L'],
      ['IE', 'IE9999999WI'],
      ['IT', 'IT99999999999'],
      ['LT', 'LT999999999'],
      ['LT', 'LT999999999999'],
      ['LU', 'LU99999999'],
      ['LV', 'LV99999999999'],
      ['MT', 'MT99999999'],
      ['NL', 'NL999999999B99'],
      ['PL', 'PL9999999999'],
      ['PT', 'PT999999999'],
      ['RO', 'RO999999999'],
      ['SE', 'SE999999999999'],
      ['SI', 'SI99999999'],
      ['SK', 'SK9999999999'],
    ];
  }


}
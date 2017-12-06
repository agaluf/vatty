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


}
<?php

namespace Vatty\tests\Unit\Vies;

use PHPUnit\Framework\TestCase;
use Vatty\Vies\ViesService;
use Meng\AsyncSoap\Guzzle\Factory as SoapFactory;

/**
 * @coversDefaultClass \Vatty\Vies\ViesService
 */
class ViesServiceTest extends TestCase {

  /**
   * Set up a test Vies service.
   *
   * @return \Vatty\Vies\ViesService
   */
  protected function setupTestViesService() {
    // Vies offers a test service to perform the test calls.
    // If Vat Number equals 100, a valid result is returned.
    // If Vat Number equals 200, an invalid result is returned.
    $wsdl = 'http://ec.europa.eu/taxation_customs/vies/checkVatTestService.wsdl';
    return new ViesService($wsdl);
  }

  /**
   * Assert that ViesService object is correctly constructed.
   *
   * @covers ::__construct
   */
  public function testConstruct() {
    $service = new ViesService();

    $wsdl = $this->readAttribute($service, 'wsdl');
    $this->assertTrue(is_string($wsdl));

    $factory = $this->readAttribute($service, 'factory');
    $this->assertTrue($factory instanceOf SoapFactory);
  }

  /**
   * Assert that a simple call can be performed.
   */
  public function testCall() {
    $service = $this->setupTestViesService();

    $result = $service->simpleValidation('DE', 'DE100');

    $this->assertSame(200, $result->getResponseCode());
  }

  /**
   * Assert that a qualified call can be performed.
   */
  public function testQualifiedCall() {
    $service = $this->setupTestViesService();
    $result = $service->qualifiedValidation('DE', 'DE100', 'DE', 'DE123456789');

    $this->assertSame(200, $result->getResponseCode());
  }

}
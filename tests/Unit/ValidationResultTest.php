<?php

namespace Vatty\tests\Unit;

use PHPUnit\Framework\TestCase;
use Vatty\ValidationResult;

/**
 * @coversDefaultClass Vatty\ValidationResult
 */
class ValidationResultTest extends TestCase {

  /**
   * Assert that ValidationResult is correctly constructed.
   *
   * @covers ::__construct
   */
  public function testConstruct() {
    $result = new ValidationResult(TRUE, 200);

    $this->assertTrue($result instanceOf ValidationResult);
  }

  /**
   * Assert that Validation Result is correctly set
   *
   * @covers ::isValid
   * @dataProvider provideValidationData
   */
  public function testValidation($isValid) {
    $result = new ValidationResult($isValid, 200);

    $this->assertSame($isValid, $result->isValid());
  }

  /**
   * Assert hat response code is correctly set.
   *
   * @covers ::getResponseCode
   */
  public function testResponseCode() {
    $result = new ValidationResult(FALSE, 404);

    $this->assertSame(404, $result->getResponseCode());
  }

  /**
   * Assert that the error message is correctly returned.
   *
   * @covers ::getErrorMessage
   */
  public function testErrorMessage() {
    $message = 'This is a simple test message';
    $result = new ValidationResult(FALSE, 404, $message);

    $this->assertSame($message, $result->getErrorMessage());
  }

  /**
   * Assert that request identifier is correctly set.
   *
   * @covers ::getRequestIdentifier
   */
  public function testRequestIdentifier() {
    $id = 'fejf094h38g408hgh4vn4gh';
    $result = new ValidationResult(TRUE, 200, '', $id);

    $this->assertSame($id, $result->getRequestIdentifier());
  }

  /**
   * Assert that the request date is correctly set.
   *
   * @covers ::getRequestDate
   */
  public function testRequestDate() {
    $date = new \DateTimeImmutable('now');
    $result = new ValidationResult(TRUE, 200, '', 'fjiwj0f923j8', $date);

    $this->assertSame($date, $result->getRequestDate());
  }

  /**
   * Provides data for validation testing.
   *
   * @return array
   */
  public function provideValidationData() {
    return [
      [TRUE],
      [FALSE]
    ];
  }


}
<?php

namespace Vatty;

/**
 * Defines ValidationResultInterface
 *
 * The object implementing this interface is returned when a validation is
 * performed or has failed.
 */
interface ValidationResultInterface {

  /**
   * Returns validation result.
   *
   * This method indicates what the result of the validation was.
   *
   * @return bool
   *   TRUE if validation has completed positively, FALSE otherwise.
   */
  public function isValid();

  /**
   * Returns HTTP response code.
   *
   * If a validation was performed successfully, this method will return
   * HTTP response 200 (OK). Response 200 DOES NOT equal valid vat - to check
   * whether validation was positive, use the method self::isValid().
   *
   * @return int
   *   The HTTP response code.
   */
  public function getResponseCode();

  /**
   * Returns response error message.
   *
   * If response code has not returned 200 (OK), an error message can be
   * returned by this method.
   *
   * @return string|null
   *   Response error message or null if no error occurred.
   */
  public function getErrorMessage();

  /**
   * Returns the request identifier.
   *
   * If a Vies request was performed, this method can be used to return the
   * unique request identifier that can be used to prove the vat number
   * validation request.
   *
   * @return string|null
   *   Request identifier if a valid Vies request was performed, null otherwise.
   */
  public function getRequestIdentifier();

  /**
   * Returns the request date.
   *
   * If a Vies request was performed, this method can be used to return the
   * date object of the request, that can be used to prove the vat number
   * validation request.
   *
   * @return \DateTimeImmutable|null
   *   An immutable DateTime object if a valid Vies request was performed, null
   *   otherwise.
   */
  public function getRequestDate();

}
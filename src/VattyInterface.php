<?php

namespace Vatty;

/**
 * Defines VattyInterface.
 *
 * The VattyInterface is the primary interface of the Vatty library.
 * All external requests are intended to be done against this interface.
 *
 * Example:
 *
 * @code
 * $vatty = new Vatty();
 * $vatty->disableCache()
 *   ->useVies()
 *   ->setRequester('DE', 'DE123456789');
 *
 * $result = $vatty->validate('AT', 'ATU12345678');
 * @endcode
 */
interface VattyInterface {

  /**
   * Set the requester information.
   *
   * This information is required to perform a request against Vies service
   * that returns a unique validation token.
   *
   * If the requester is not set, only a simple Vies validation can be
   * performed.
   *
   * @param string $countryCode
   *   Country code in ISO 3166-1 alpha-2 format.
   * @param $vatNumber
   *   The requester's Vat number.
   *
   * @return $this
   *   The called \Vatty\VattyInterface object.
   */
  public function setRequester(/* string */ $countryCode, /* string */ $vatNumber);

  /**
   * Activates the Vies validation.
   *
   * If this method is set, simple local validation will be followed by a
   * request against the Vies SOAP service to validate the Tax number against
   * actual national databases.
   *
   * Note: occasionally, the Vies validation can fail because either Vies or
   * the national database behind Vies is offline. In that case, the validator
   * will return an http error 503. It is the responsibility of the caller to
   * catch the error and try again at a later time.
   *
   * @param bool $useVies
   *   Whether the Vies service should be used. Defaults to TRUE.
   *
   * @return $this
   *   The called \Vatty\VattyInterface object.
   */
  public function useVies(/* bool */ $useVies = TRUE);

  /**
   * Disables internal 24 hour cache.
   *
   * In order to prevent excessive requests against the Vies service by
   * validating too many of the same Tax numbers, Vatty employs a simple
   * internal file-based cache with 24-hour lifetime. By calling this method,
   * you can disable the cache.
   *
   * @warning it is the responsibility of the caller to ensure that requests
   * against Vies are limited to minimum.
   *
   * @return $this
   *   The called \Vatty\VattyInterface object.
   */
  public function disableCache();

  /**
   * Performs the validation of the passed-in Vat number and country.
   *
   * @param string $countryCode
   *   Country code in ISO 3166-1 alpha-2 format.
   * @param $vatNumber
   *   The Vat number to validate.
   *
   * @return ValidationResultInterface
   *   The validation result.
   */
  public function validate(/* string */ $countryCode, /* string */ $vatNumber);

}
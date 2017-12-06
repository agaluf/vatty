<?php

namespace Vatty;

interface VatInterface {

  /**
   * Validate the vat number.
   *
   * @param string $vat
   *
   * @return bool
   *   TRUE if vat number is structurally valid, FALSE otherwise.
   */
  public function validate(/* string */ $vat);

  /**
   * Return an array of regular expressions that will be used for validation.
   *
   * @return array
   */
  public function getRegEx();

  /**
   * Return the country code in ISO 3166-1 alpha-2 format.
   *
   * @return string
   */
  public function getCountryCode();

}
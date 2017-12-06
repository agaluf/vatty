<?php

namespace Vatty\Entity;

use Vatty\VatInterface;

/**
 * Defines Vat class, which contains validation rules for a particular country.
 */
class Vat implements VatInterface {

  /**
   * The country code in ISO 3166-1 alpha-2 format.
   *
   * @var string
   */
  protected $countryCode;

  /**
   * An array of regular expressions to validate against.
   *
   * @var array
   */
  protected $regEx;

  /**
   * Construct a new Vat object.
   *
   * @param string $countryCode
   *   The country code in ISO 3166-1 alpha-2 format.
   *
   * @param array $regEx
   *   An array of regular expressions to validate against.
   */
  public function __construct(/* string */ $countryCode, array $regEx) {
    $this->countryCode = $countryCode;
    $this->regEx = $regEx;
  }

  /**
   * {@inheritdoc}
   */
  public function validate($vat) {
    $valid = FALSE;

    foreach ($this->getRegEx() as $regEx) {
      $regEx = "/$regEx/";
      preg_match($regEx, $vat, $matches);

      if (isset($matches[0]) && $matches[0] === $vat) {
        $valid = TRUE;
        break;
      }
    }

    return $valid;
  }

  /**
   * {@inheritdoc}
   */
  public function getRegEx() {
    return $this->regEx;
  }

  /**
   * {@inheritdoc}
   */
  public function getCountryCode() {
    return $this->countryCode;
  }

}
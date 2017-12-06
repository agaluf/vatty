<?php

namespace Vatty\Repository;

use Vatty\Entity\Vat;
use Vatty\Exception\UnknownCountryException;

/**
 * Provides Vat data based on passed-in definitions.
 */
class VatRepository implements VatRepositoryInterface {

  /**
   * Vat definitions.
   *
   * @array
   *   An array of vat definitions, keyed by country codes
   *   in ISO 3166-1 alpha-2 format.
   */
  protected $definitions = [];

  /**
   * Creates a VatRepository object.
   *
   * @param array $definitions
   *   An array of vat definitions. If no definitions are passed, the default is
   *   loaded from the included json.
   */
  public function __construct($definitions = NULL) {
    $this->definitions = $definitions ? $definitions : json_decode(file_get_contents(__DIR__ . '/../../resources/vat.json'), TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function get($countryCode) {
    if (!isset($this->definitions[$countryCode])) {
      throw new UnknownCountryException($countryCode);
    }

    return new Vat($countryCode, $this->definitions[$countryCode]);
  }

  /**
   * {@inheritdoc}
   */
  public function getAll() {
    $vats = [];
    foreach ($this->definitions as $countryCode => $definition) {
      $vats[$countryCode] = new Vat($countryCode, $definition);
    }

    return $vats;
  }

}
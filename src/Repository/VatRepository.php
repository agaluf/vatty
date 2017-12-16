<?php

namespace Vatty\Repository;

use Vatty\Entity\Vat;
use Vatty\Exception\UnknownCountryException;

/**
 * Provides Vat data based on passed-in definitions.
 */
class VatRepository implements VatRepositoryInterface {

  /**
   * The path to the default json file.
   *
   * @var string
   */
  protected $jsonPath = __DIR__ . '/../../resources/vat.json';

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
   *
   * @throws \Exception
   *   Throws an exception if json cannot be read.
   */
  public function __construct(array $definitions = NULL) {
    $this->definitions = $definitions ? $definitions : $this->loadDefaultDefinitions();
  }

  /**
   * {@inheritdoc}
   */
  public function get($countryCode) {
    if (!isset($this->definitions[$countryCode])) {
      throw new UnknownCountryException();
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

  /**
   * Helper method to load the default definitions.
   *
   * @return array
   *   An array of loaded definitions.
   *
   * @throws \Exception
   *   Throws an exception if json cannot be read.
   */
  protected function loadDefaultDefinitions() {
    $definitions = json_decode(file_get_contents($this->jsonPath), TRUE);
    if (!$definitions) {
      // An error has occured when trying to parse the json, which usually
      // means that the JSON is corrupt. Throw a general error here.
      throw new \Exception('Invalid Json.');
    }

    return $definitions;
  }

}
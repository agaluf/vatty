<?php

namespace Vatty\Repository;

interface VatRepositoryInterface {

  /**
   * Get the vat definitions for the given country.
   *
   * @param $countryCode
   *   Country code in ISO 3166-1 alpha-2 format.
   *
   * @throws \Vatty\Exception\UnknownCountryException
   *   Throws exception if countryCode is not found in the definitions.
   *
   * @return \Vatty\Entity\Vat
   *   The Vat object.
   */
  public function get($countryCode);

  /**
   * Get all vat definitions.
   *
   * @return array
   */
  public function getAll();

}
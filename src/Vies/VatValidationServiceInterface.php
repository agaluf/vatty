<?php

namespace Vatty\Vies;

interface VatValidationServiceInterface {

  /**
   * Perform a simple validation of the Vat number.
   *
   * With the simple validation, we use the service to check if the vat number
   * is actually assigned.
   *
   * @param string $countryCode
   *   The country that the vat number should belong to.
   * @param string $vatNumber
   *   The vat number to validate.
   *
   * @return \Vatty\ValidationResult
   *   The validation result.
   */
  public function simpleValidation(
    /* string */ $countryCode,
    /* string */ $vatNumber
  );

  /**
   * Perform a qualified validation of the Vat number.
   *
   * This validation checks if the vat number is assigned to a company.
   * The result is matched to the requester and a unique string is returned
   * which can be used to prove that a validation has been done.
   *
   * @param string $countryCode
   *   The country code that the vat number should belong to.
   * @param string $vatNumber
   *   The vat number to validate.
   * @param string $requesterCountryCode
   *   The country code of the requester.
   * @param string $requesterVatNumber
   *   The vat number of the requester.
   *
   * @return \Vatty\ValidationResult
   *   The validation result.
   */
  public function qualifiedValidation(
    /* string */ $countryCode,
    /* string */ $vatNumber,
    /* string */ $requesterCountryCode,
    /* string */ $requesterVatNumber
  );

}
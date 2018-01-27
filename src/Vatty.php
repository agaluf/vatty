<?php

namespace Vatty;

use Vatty\Repository\VatRepository;
use Vatty\Repository\VatRepositoryInterface;
use Vatty\Vies\VatValidationServiceInterface;
use Vatty\Vies\ViesService;

class Vatty implements VattyInterface {

  /**
   * Contains the Vat repository.
   *
   * @var \Vatty\Repository\VatRepositoryInterface
   */
  protected $repository;

  /**
   * Contains the Vat validation service.
   *
   * @var \Vatty\Vies\VatValidationServiceInterface
   */
  protected $validationService;

  /**
   * Contains the Validation cache.
   *
   * @var \Vatty\Cache\ValidationCache
   */
  protected $cache;

  /**
   * Whether the built-in file cache should be used.
   *
   * @var bool
   */
  protected $useCache = TRUE;

  /**
   * Whether to use the validation service.
   *
   * @var bool
   */
  protected $vies;

  /**
   * The requester country.
   *
   * @var string
   */
  protected $requesterCountry;

  /**
   * The requester vat.
   *
   * @var string
   */
  protected $requesterVat;

  /**
   * Constructs a new \Vatty\Vatty object.
   *
   * @param \Vatty\Repository\VatRepositoryInterface
   *   The Vat repository.
   * @param \Vatty\Vies\VatValidationServiceInterface
   *   The vat validation service.
   */
  public function __construct(
    VatRepositoryInterface $vatRepository = NULL,
    VatValidationServiceInterface $validationService = NULL
  ) {
    $this->repository = !is_null($vatRepository) ? $vatRepository : new VatRepository();
    $this->validationService = !is_null($validationService) ? $validationService : new ViesService();
  }

  /**
   * {@inheritdoc}
   */
  public function setRequester(/* string */ $countryCode, /* string */ $vatNumber) {
    $this->requesterCountry = $countryCode;
    $this->requesterVat = $vatNumber;
    return $this;
  }

  public function requesterEmpty() {
    return (empty($this->requesterCountry) || empty($this->requesterVat));
  }

  /**
   * {@inheritdoc}
   */
  public function useVies(/* bool */ $useVies = TRUE) {
    $this->vies = $useVies;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function disableCache() {
    $this->useCache = FALSE;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function validate(/* string */ $countryCode, /* string */ $vatNumber) {
    try {
      $vat = $this->repository->get($countryCode);

      // Vies service expects the Vat number to be passed without any spaces,
      // even though official Vat numbers may contain them (see France, GB).
      // To simplify and unify the validations, we remove the spaces here.
      $vatNumber = str_replace(' ', '', $vatNumber);

      $syntaxValid = $vat->validate($vatNumber);

      if ($syntaxValid && $this->vies === TRUE) {
        $result = $this->performViesValidation($countryCode, $vatNumber);
      }
      else {
        $result = new ValidationResult($syntaxValid, 200);
      }
    }
    catch (\Exception $e) {
      $result = new ValidationResult(FALSE, $e->getCode(), $e->getMessage());
    }

    return $result;
  }

  protected function performViesValidation(/* string */ $countryCode, /* string */ $vatNumber) {
    if ($this->requesterEmpty()) {
      $result = $this->validationService->simpleValidation($countryCode, $vatNumber);
    }
    else {
      $result = $this->validationService->qualifiedValidation($countryCode, $vatNumber, $this->requesterCountry, $this->requesterVat);
    }

    return $result;
  }

}
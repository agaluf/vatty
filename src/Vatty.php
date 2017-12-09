<?php

namespace Vatty;

use Vatty\Repository\VatRepository;
use Vatty\Repository\VatRepositoryInterface;

class Vatty implements VattyInterface {

  /**
   * Contains the Vat repository.
   *
   * @var \Vatty\Repository\VatRepositoryInterface
   */
  protected $repository;

  /**
   * Constructs a new \Vatty\Vatty object.
   *
   * @param \Vatty\Repository\VatRepositoryInterface
   */
  public function __construct(VatRepositoryInterface $vatRepository = NULL) {
    if (is_null($vatRepository)) {
      $vatRepository = new VatRepository();
    }
    $this->repository = $vatRepository;
  }

  /**
   * {@inheritdoc}
   */
  public function setRequester(/* string */ $countryCode, /* string */ $vatNumber) {
    // TODO: Implement setRequester() method.
  }

  /**
   * {@inheritdoc}
   */
  public function useVies(/* bool */ $useVies = TRUE) {
    // TODO: Implement useVies() method.
  }

  /**
   * {@inheritdoc}
   */
  public function disableCache() {
    // TODO: Implement disableCache() method.
  }

  /**
   * {@inheritdoc}
   */
  public function validate(/* string */ $countryCode, /* string */ $vatNumber) {
    // TODO: Implement validate() method.

    try {
      $vat = $this->repository->get($countryCode);

      $syntaxCheck = $vat->validate($vatNumber);
      $result = new ValidationResult($syntaxCheck, 200);

      // @todo: Syntax validation succeeded, we can do a full validation here.
    }
    catch (\Exception $e) {
      $result = new ValidationResult(FALSE, $e->getCode(), $e->getMessage());
    }

    return $result;
  }

}
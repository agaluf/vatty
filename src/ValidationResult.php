<?php

namespace Vatty;

class ValidationResult implements ValidationResultInterface {

  /**
   * Whether the validation was successful.
   *
   * @var bool
   */
  protected $valid;

  /**
   * Validation response code.
   *
   * @var int
   */
  protected $responseCode;

  /**
   * Error message, if response code is not 200 (OK).
   *
   * @var string
   */
  protected $errorMessage;

  /**
   * Request identifier string, if the Vies service was used.
   *
   * @var string
   */
  protected $requestIdentifier;

  /**
   * Request date, if the Vies service was used.
   *
   * @var \DateTimeImmutable
   */
  protected $requestDate;

  /**
   * Constructs a new ValidationResult object.
   *
   * @param bool $valid
   *   Whether the validation was successful.
   * @param int $responseCode
   *   Validation response code.
   * @param string $errorMessage
   *   Error message, if response code is not 200 (OK).
   * @param string $requestIdentifier
   *   Response code if the Vies service was used, NULL otherwise.
   * @param \DateTimeImmutable $requestDate
   *   Response date if the Vies service was used, NULL otherwise.
   */
  public function __construct(/* bool */ $valid,
    /* int */ $responseCode,
    /* string */ $errorMessage = '',
    /* string */ $requestIdentifier = NULL,
    /* \DateTime */ $requestDate = NULL
  ) {
    $this->valid = $valid;
    $this->responseCode = $responseCode;
    $this->errorMessage = $errorMessage;
    $this->requestIdentifier = $requestIdentifier;
    $this->requestDate = $requestDate;
  }

  /**
   * {@inheritdoc}
   */
  public function isValid() {
    return $this->valid;
  }

  /**
   * {@inheritdoc}
   */
  public function getResponseCode() {
    return $this->responseCode;
  }

  /**
   * {@inheritdoc}
   */
  public function getErrorMessage() {
    return $this->errorMessage;
  }

  /**
   * {@inheritdoc}
   */
  public function getRequestIdentifier() {
    return $this->requestIdentifier;
  }

  /**
   * {@inheritdoc}
   */
  public function getRequestDate() {
    return $this->requestDate;
  }
}
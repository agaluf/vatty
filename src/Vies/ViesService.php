<?php

namespace Vatty\Vies;

use GuzzleHttp\Client as GuzzleClient;
use Meng\AsyncSoap\Guzzle\Factory as SoapFactory;
use Vatty\ValidationResult;

class ViesService implements VatValidationServiceInterface {

  /**
   * The call to execute.
   *
   * This call must be found in the provided wsdl.
   *
   * @var string
   */
  protected $call;

  /**
   * An array of parameters to pass in the call.
   *
   * @var array
   */
  protected $params;

  /**
   * The wsdl url.
   *
   * @var string
   */
  protected $wsdl;

  /**
   * The guzzle soap factory object.
   *
   * @var \Meng\AsyncSoap\Guzzle\Factory
   */
  protected $factory;

  public function __construct($wsdl = NULL, $factory = NULL) {
    $this->wsdl = $wsdl ? $wsdl : 'http://ec.europa.eu/taxation_customs/vies/checkVatTestService.wsdl';
    $this->factory = $factory ? $factory : new SoapFactory();
  }

  /**
   * {@inheritdoc}
   */
  public function simpleValidation(/* string */ $countryCode, /* string */ $vatNumber) {
    $this->call = 'checkVat';
    $this->params = [
      'countryCode' => $countryCode,
      'vatNumber' => substr($vatNumber, 2),
    ];

    $result = $this->executeCall();

    return new ValidationResult($result->valid, 200);
  }

  /**
   * {@inheritdoc}
   */
  public function qualifiedValidation(/* string */ $countryCode, /* string */ $vatNumber, /* string */ $requesterCountryCode, /* string */ $requesterVatNumber) {
    $this->call = 'checkVatApprox';
    $this->params = [
      'countryCode' => $countryCode,
      'vatNumber' => substr($vatNumber, 2),
      'requesterCountryCode' => $requesterCountryCode,
      'requesterVatNumber' => substr($requesterVatNumber, 2),
    ];

    $result = $this->executeCall();

    return new ValidationResult($result->valid, 200);
  }

  /**
   * A helper method to execute the actual call.
   *
   * Since the call uses the SoapClient, an stdClass is returned.
   *
   * @return \stdClass
   */
  protected function executeCall() {
    $client = $this->buildClient();

    return $client->call($this->call, [$this->params]);
  }

  /**
   * A helper method to prepare the client.
   *
   * @return \Meng\AsyncSoap\SoapClientInterface
   */
  protected function buildClient() {
    return $this->factory->create(new GuzzleClient(), $this->wsdl);
  }

}
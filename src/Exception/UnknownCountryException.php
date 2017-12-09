<?php

namespace Vatty\Exception;

class UnknownCountryException extends \InvalidArgumentException {

  protected $message = 'Unknown country.';

  protected $code = 404;

}
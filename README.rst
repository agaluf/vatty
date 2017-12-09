Vatty
=====

PHP 5.6+ EU VAT validation library

Vatty provides a simple API to validate EU Tax IDs both with simple local validation and by using the Vies Vat ID validation service.

Requirements
------------

Vatty requires PHP 5.6+ and Composer. Vies validation service additionally requires PHP SOAP extension.

Installation
------------

Add ``tufy/vatty`` as a require dependency in your ``composer.json`` file:

.. code-block:: bash

    composer require tufy/vatty

Usage
-----

Simple validation

Simple validation checks if the passed-in validation number matches the expected format. It only validates if the
syntax for the given country is correct.

.. code-block:: php

use Vatty\Vatty;

$validator = new Vatty();
$result = $validator->validate('DE', 'DE123456789');


Simple Vies validation

@todo: implement

Qualified Vies validation

@todo: implement

Warning: both Vies validations require SoapClient to initiate a request against Vies validation service.
If you do not have SoapClient active, the validation will not be performed.

@todo: does it make sense to bypass SoapClient and implement the request as a Rest call, with simulated SOAP?


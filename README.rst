Vatty
=====

PHP 5.6+ EU VAT validation library

Vatty provides a simple API to validate EU Tax IDs both with simple local syntax check and by using the (VAT Information Exchange System) VAT validation service.

Requirements
============

Vatty requires PHP 5.6+ and Composer. Vies validation service additionally requires PHP SOAP extension.

Installation
============

Add ``tufy/vatty`` as a require dependency in your ``composer.json`` file:

.. code-block:: bash

    composer require tufy/vatty

Usage
=====

Simple validation
-----------------

Simple validation checks if the passed-in VAT matches the expected format. It only validates that the
syntax for the given country is correct.

.. code-block:: php

    use Vatty\Vatty;

    $validator = new Vatty();
    $result = $validator->validate('DE', 'DE123456789');


Simple Vies validation
----------------------

To perform a simple Vies validation of the given VAT, you need to activate the Vies service:

.. code-block:: php

    use Vatty\Vatty;

    $validator = new Vatty();
    $validator->useVies();
    $result = $validator->validate('DE', 'DE123456789');

This will perform a VAT validation with Vies and determine if the VAT is assigned to an active subject.


Qualified Vies validation
-------------------------

Some countries' laws (such as for instance Austrian) demand that the companies must prove the validation of the VAT.

Vies will automatically return a unique request identifier string if the requester information has been provided. To get the request identifier, pass the requester information to the validator:

.. code-block:: php

    use Vatty\Vatty;

    $validator = new Vatty();
    $validator->useVies();
    $validator->setRequester('DE', 'DE987654321');
    $result = $validator->validate('DE', 'DE123456789');


Warning
-------

both Vies validations currently require the Soap extension to initiate a request against Vies validation service.
If you do not have the Soap extension active, the validation will fall back to the syntax check.

Do you require Vies validation without the Soap extension? Open an issue and I'll see what I can do.
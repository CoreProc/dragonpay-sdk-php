<?php

namespace Coreproc\Dragonpay\UrlGenerator;

use Coreproc\Dragonpay\Exceptions\ValidationException;
use Valitron\Validator;

abstract class UrlGenerator
{

    /**
     * Dragonpay Payment Switch Base URL
     *
     * @var string
     */
    protected $basePaymentUrl = 'https://gw.dragonpay.ph/Pay.aspx';

    /**
     * Dragonpay Payment Switch Test URL
     *
     * @var string
     */
    protected $testPaymentUrl = 'http://test.dragonpay.ph/Pay.aspx';

    /**
     * @var array Validation rules
     */
    protected $rules = [];

    /**
     * Validate required parameters for URL Generation.
     *
     * @param array $params
     * @throws ValidationException
     */
    protected function validate(array $params)
    {
        $validator = new Validator($params);

        $validator->rule('required', $this->rules);

        if ( ! $validator->validate()) {
            $errors = '';

            foreach ($validator->errors() as $key => $value) {
                $errors .= $key . ' is required. ';
            }

            throw new ValidationException($errors);
        }
    }

}
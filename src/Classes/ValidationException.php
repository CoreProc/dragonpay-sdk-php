<?php namespace Coreproc\Dragonpay\Classes;

class ValidationException extends \Exception {

    /**
     * @var string
     */
    private $errors;

    public function __construct($message, $errors)
    {
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }

}
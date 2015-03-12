<?php namespace Coreproc\Dragonpay;

class DragonpayClient
{

    private $merchantId;

    private $secretKey;

    private $merchantPassword;

    public function __construct($merchantId, $secretKey, $merchantPassword)
    {
        $this->merchantId = $merchantId;
        $this->secretKey = $secretKey;
        $this->merchantPassword = $merchantPassword;
    }

    public function getMerchantId()
    {
        return $this->merchantId;
    }

    public function getSecretKey()
    {
        return $this->secretKey;
    }

    public function getMerchantPassword()
    {
        return $this->merchantPassword;
    }

}



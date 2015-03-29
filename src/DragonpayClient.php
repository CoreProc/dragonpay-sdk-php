<?php

namespace Coreproc\Dragonpay;

class DragonpayClient
{

    private $merchantId;

    private $merchantPassword;

    public function __construct(array $credentials, $logging = false, $logDirectory = null)
    {
        $this->merchantId = $credentials['merchantId'];
        $this->merchantPassword = $credentials['merchantPassword'];
    }

    /**
     * @return mixed
     */
    public function getMerchantId()
    {
        return $this->merchantId;
    }

    /**
     * @return mixed
     */
    public function getMerchantPassword()
    {
        return $this->merchantPassword;
    }

}
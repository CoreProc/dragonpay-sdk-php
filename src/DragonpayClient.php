<?php

namespace Coreproc\Dragonpay;

class DragonpayClient
{

    private $merchantId;

    private $merchantPassword;

    public function __construct(array $merchantCredentials, $logging = false, $logDirectory = null)
    {
        $this->merchantId = $merchantCredentials['merchantId'];
        $this->merchantPassword = $merchantCredentials['merchantPassword'];
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
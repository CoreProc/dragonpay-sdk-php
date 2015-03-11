<?php namespace Coreproc\Dragonpay;

use Coreproc\Dragonpay\Classes\URLGenerator;

class DragonpayService
{

    /**
     * @var URLGenerator
     */
    private $urlGenerator;

    private $merchantId;

    private $secretKey;

    private $merchantPassword;

    public function __construct($merchantId, $secretKey, $merchantPassword)
    {
        $this->urlGenerator = new URLGenerator();
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

    /**
     * Get the generated URL for redirecting to Payment Switch.
     *
     * @param array $data
     * @return string
     */
    public function getUrl(array $data)
    {
        $data['merchantId'] = $this->merchantId;
        $data['secretKey'] = $this->secretKey;

        return $this->urlGenerator->generate($data);
    }

}



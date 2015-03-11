<?php namespace Coreproc\Dragonpay\Classes;

use Coreproc\Dragonpay\DragonpayClient;

class Checkout
{

    /**
     * @var DragonpayClient
     */
    private $client;

    /**
     * @var URLGenerator
     */
    private $urlGenerator;

    public function __construct(DragonpayClient $client)
    {
        $this->client = $client;
        $this->urlGenerator = new URLGenerator();
    }

    /**
     * Get the generated URL for redirecting to Payment Switch.
     *
     * @param array $data
     * @return string
     */
    public function getUrl(array $data)
    {
        $data['merchantId'] = $this->client->getMerchantId();
        $data['secretKey'] = $this->client->getSecretKey();

        return $this->urlGenerator->generate($data);
    }

}
<?php

namespace Coreproc\Dragonpay\Classes;

use Coreproc\Dragonpay\DragonpayClient;
use Coreproc\Dragonpay\Classes\URLGenerator\UrlGeneratorFactory;

class Checkout
{

    /**
     * @var DragonpayClient
     */
    private $client;

    public function __construct(DragonpayClient $client, $webService = 'REST')
    {
        $this->client = $client;
        $this->urlGenerator = UrlGeneratorFactory::create($webService);
    }

    /**
     * Get the generated URL to the Dragonpay Payment Switch API.
     *
     * @param array $params
     * @return string
     */
    public function getURL(array $params)
    {
        $params['merchantId'] = $this->client->getMerchantId();
        $params['password'] = $this->client->getMerchantPassword();

        return $this->urlGenerator->generate($params);
    }

    /**
     * @param array $params
     */
    public function redirect(array $params)
    {
        $url = $this->getURL($params);

        header("Location:{$url}");
    }

}
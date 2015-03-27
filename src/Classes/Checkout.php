<?php

namespace Coreproc\Dragonpay\Classes;

use Coreproc\Dragonpay\DragonpayClient;
use Coreproc\Dragonpay\Classes\URLGenerator\URLGeneratorFactory;

class Checkout
{

    /**
     * @var DragonpayClient
     */
    private $client;

    public function __construct(DragonpayClient $client, $webService = 'REST')
    {
        $this->client = $client;
        $this->urlGenerator = URLGeneratorFactory::create($webService);
    }

    /**
     * Get the URL to the Dragonpay Payment Switch API.
     */
    public function getURL(array $params)
    {
        $params['merchantId'] = $this->client->getMerchantId();
        $params['password'] = $this->client->getMerchantPassword();

        return $this->urlGenerator->generate($params);
    }

    public function redirect(array $params)
    {
        $url = $this->getURL($params);

        header("Location:{$url}");
    }

}
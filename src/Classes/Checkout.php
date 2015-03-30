<?php

namespace Coreproc\Dragonpay\Classes;

use Coreproc\Dragonpay\DragonpayClient;
use Coreproc\Dragonpay\Classes\UrlGenerator\UrlGeneratorFactory;

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
     * @TODO Clean up logging
     */
    public function getURL(array $params)
    {
        $params['merchantId'] = $this->client->getMerchantId();
        $params['password'] = $this->client->getMerchantPassword();

        if ($this->client->isLoggingEnabled()) {
            $logMessage = "[dragonpay-sdk][url-generation] Generating URL to Dragonpay Payment Switch.";
            $this->client->getLogger()->info($logMessage);
        }

        $url = $this->urlGenerator->generate($params);

        if ($url && $this->client->isLoggingEnabled()) {
            $logMessage = "[dragonpay-sdk][url-generation] Successfully generated URL to Dragonpay Payment Switch. URL: \"{$url}\"";
            $this->client->getLogger()->info($logMessage);
        }

        return $url;
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
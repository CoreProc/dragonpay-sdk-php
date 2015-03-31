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
     * @param null|string $filter
     * @return string
     * @TODO Clean up logging
     */
    public function getUrl(array $params, $filter = null)
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

        if ($filter !== null) {
            $url = $this->addFilter($url, $filter);
        }

        return $url;
    }

    /**
     * Generate the URL to Dragonpay Payment Switch API then redirect.
     *
     * @param array $params
     * @param null|string $filter
     */
    public function redirect(array $params, $filter = null)
    {
        $url = $this->getUrl($params, $filter);

        header("Location:{$url}");
    }

    /**
     * Filter the payment channels that appears on Dragonpay's payment
     * selection page.
     *
     * @param string $url
     * @param string $filter
     * @return string
     */
    private function addFilter($url, $filter)
    {
        switch ($filter) {
            case 'online_banking':
                $filter = '&mode=1';
                break;
            case 'otc_non_bank':
                $filter = '&mode=2';
                break;
            case 'paypal':
                $filter = '&mode=32';
                break;
            case 'credit_card':
                $filter = '&mode=64';
                break;
            case 'mobile':
                $filter = '&mode=128';
                break;
            case 'international_otc':
                $filter = '&mode=256';
                break;
            case 'gcash_direct':
                $filter = '&procid=GCSH';
                break;
            case 'credit_card_direct':
                $filter = '&procid=CC';
                break;
            case 'paypal_direct':
                $filter = '&procid=PYPL';
                break;
            default:
                $filter = '';
                break;
        }

        $url = $url . $filter;

        return $url;
    }

}
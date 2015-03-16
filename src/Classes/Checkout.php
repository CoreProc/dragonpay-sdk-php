<?php

namespace Coreproc\Dragonpay\Classes;

use Coreproc\Dragonpay\DragonpayClient;

class Checkout
{

    /**
     * @var DragonpayClient
     */
    private $client;

    /**
     * @var PaymentSwitchURLGenerator
     */
    private $generator;

    /**
     * @var null|\Katzgrau\KLogger\Logger
     */
    private $log = null;

    public function __construct(DragonpayClient $client)
    {
        $this->client = $client;
        $this->generator = new PaymentSwitchURLGenerator();

        if ($this->client->isLoggingEnabled()) {
            $this->log = $this->client->getLogger();
        }
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

        if ($this->client->isLoggingEnabled()) {
            $logMessage = '[dragonpay-sdk][url-generation] Generating URL to '
                . 'Payment Switch API.';
            $this->log->info($logMessage);
        }

        $url = $this->generator->generate($data);

        if ($url && $this->client->isLoggingEnabled()) {
            $logMessage = '[dragonpay-sdk][url-generation] Successfully '
                . 'generated URL to Payment Switch API.';
            $this->log->info($logMessage);
        }

        return $url;
    }

}
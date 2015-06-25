<?php

namespace Coreproc\Dragonpay\MerchantService;

interface BillingServiceInterface
{

    /**
     * Send billing information of customer's billing address to the Dragonpay
     * Payment Switch API for additional fraud checking.
     *
     * @param $merchantId
     * @param array $params
     * @param $testing
     * @return mixed
     */
    public function sendBillingInformation($merchantId, array $params, $testing);

}
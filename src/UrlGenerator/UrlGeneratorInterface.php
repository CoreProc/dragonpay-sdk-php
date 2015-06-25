<?php

namespace Coreproc\Dragonpay\UrlGenerator;

interface UrlGeneratorInterface
{

    /**
     * Generate the URL to Dragonpay Payment Switch.
     *
     * @param array $params
     * @param $testing
     * @return string
     */
    public function generate(array $params, $testing);

}
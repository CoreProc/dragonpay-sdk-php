<?php

namespace Coreproc\Dragonpay\UrlGenerator;

interface UrlGeneratorInterface
{

    /**
     * Generate the URL to Dragonpay Payment Switch.
     *
     * @param array $params
     * @return string
     */
    public function generate(array $params);

}
<?php

namespace Coreproc\Dragonpay\Classes\UrlGenerator;

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
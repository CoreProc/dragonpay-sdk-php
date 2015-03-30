<?php

namespace Coreproc\Dragonpay\Classes\UrlGenerator;

interface UrlGeneratorInterface
{

    /**
     * Generate the URL to Dragonpay Payment Switch.
     *
     * @param $params
     * @return string
     */
    public function generate($params);

}
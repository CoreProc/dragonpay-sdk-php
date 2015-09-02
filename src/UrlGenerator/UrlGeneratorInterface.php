<?php

namespace Coreproc\Dragonpay\UrlGenerator;

interface UrlGeneratorInterface
{

    /**
     * @param array $params
     * @param null $filter
     * @return string
     */
    public function generate(array $params, $filter = null);

}
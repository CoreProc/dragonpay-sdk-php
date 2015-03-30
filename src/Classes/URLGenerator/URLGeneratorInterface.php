<?php

namespace Coreproc\Dragonpay\Classes\URLGenerator;

interface URLGeneratorInterface
{

    /**
     * Generate the URL to Dragonpay Payment Switch.
     *
     * @param $params
     * @return string
     */
    public function generate($params);

}
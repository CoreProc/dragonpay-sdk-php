<?php

namespace Coreproc\Dragonpay\Logging;

trait Loggable
{

    /**
     * @param string $message Log message
     * @param string $level Log level
     */
    private function log($message, $level = 'info')
    {
        if ($this->client->isLoggingEnabled()) {
            $this->client->getLogger()->log($level, $message);
        }
    }

}
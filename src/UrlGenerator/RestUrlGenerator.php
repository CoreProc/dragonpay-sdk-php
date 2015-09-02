<?php

namespace Coreproc\Dragonpay\UrlGenerator;

class RestUrlGenerator implements UrlGeneratorInterface
{

    /**
     * @var string Dragonpay Payment Switch API base URI
     */
    protected $baseUri;

    public function __construct($testing = false)
    {
        $this->baseUri = $testing ? TESTING_BASE_URI : PRODUCTION_BASE_URI;
    }

    /**
     * @param array $params
     * @param null $filter
     * @return string
     * @TODO Add functionality for additional optional params
     */
    public function generate(array $params, $filter = null)
    {
        $queryString = sprintf(
            '?merchantid=%s&txnid=%s&amount=%s&ccy=%s&description=%s&email=%s',
            urlencode($params['merchantId']),
            urlencode($params['transactionId']),
            urlencode($params['amount']),
            urlencode($params['currency']),
            urlencode($params['description']),
            urlencode($params['email'])
        );

        // Generate digest and append it to the query string
        $queryString .= '&digest=' . $this->generateDigest($params);

        // If there's a filter, append its code to the query string
        if ($filter !== null) {
            $queryString .= $this->getFilterCode($filter);
        }

        // Return the full URL
        return $this->baseUri . '/Pay.aspx' . $queryString;
    }

    /**
     * @param $params
     * @return string
     */
    private function generateDigest($params)
    {
        $message = sprintf(
            '%s:%s:%s:%s:%s:%s:%s',
            $params['merchantId'],
            $params['transactionId'],
            $params['amount'],
            $params['currency'],
            $params['description'],
            $params['email'],
            $params['merchantPassword']
        );

        return sha1($message);
    }

    /**
     * Note: Is the switch case an indication of code smell?
     * (Use polymorphism instead?)
     *
     * @param $filter
     * @return string
     */
    private function getFilterCode($filter)
    {
        switch ($filter) {
            case 'online_banking':
                $code = '&mode=1';
                break;
            case 'otc_banking_atm':
                $code = '&mode=2';
                break;
            case 'otc_non_bank':
                $code = '&mode=4';
                break;
            case 'paypal':
                $code = '&mode=32';
                break;
            case 'credit_card':
                $code = '&mode=64';
                break;
            case 'mobile':
                $code = '&mode=128';
                break;
            case 'international_otc':
                $code = '&mode=256';
                break;
            case 'gcash_direct':
                $code = '&procid=GCSH';
                break;
            case 'credit_card_direct':
                $code = '&procid=CC';
                break;
            case 'paypal_direct':
                $code = '&procid=PYPL';
                break;
            default:
                $code = '';
                break;
        }

        return $code;
    }

}
<?php namespace Coreproc\Dragonpay\Classes;

class PaymentSwitchURLGenerator
{

    /**
     * @var string Payment Switch Base Payment URL
     */
    private $baseUrl = 'https://gw.dragonpay.ph/Pay.aspx';

    /**
     * @var string Payment Switch Test Payment URL
     */
    private $testUrl = 'http://test.dragonpay.ph/Pay.aspx';

    /**
     * Generate the URL for redirecting the merchant to Payment Switch.
     *
     * @param array $data
     * @return string
     */
    public function generate(array $data)
    {
        $data['digest'] = $this->generateDigest($data);

        $this->validate($data);

        $params = 'merchantid=' . urlencode($data['merchantId'])
            . '&txnid=' . urlencode($data['transactionId'])
            . '&amount=' . urlencode($data['amount'])
            . '&ccy=' . urlencode($data['currency'])
            . '&description=' . urlencode($data['description'])
            . '&email=' . urlencode($data['email']);

        // OPTIONAL: values to be posted back to merchant url when completed
        if (isset($data['param1'])) {
            $params .= '%26param1=' . urlencode($data['param1']);
        }

        // OPTIONAL: values to be posted back to merchant url when completed
        if (isset($data['param2'])) {
            $params .= '%26param2=' . urlencode($data['param2']);
        }

        // Append generated digest
        $params .= '&digest=' . urlencode($data['digest']);

        $url = "$this->testUrl?$params";

        return $url;
    }

    /**
     * Generate a digest to be appended to the generated URL's params.
     *
     * @param array $data
     * @return string
     */
    private function generateDigest(array $data)
    {
        $message = "{$data['merchantId']}:{$data['transactionId']}:{$data['amount']}"
            . ":{$data['currency']}:{$data['description']}:{$data['email']}"
            . ":{$data['secretKey']}";

        return sha1($message);
    }

    /**
     * Validate fed inputs to the URL generator.
     *
     * @param array $data
     * @throws ValidationException
     */
    private function validate(array $data)
    {
        $required = [
            'merchantId',
            'transactionId',
            'amount',
            'currency',
            'description',
            'email',
            'digest',
        ];

        $errors = [];

        foreach ($required as $key) {
            if ( ! array_key_exists($key, $data) || empty($data[$key])) {
                $errors[] = "The {$key} is required";
            }
        }

        if (count($errors)) {
            throw new ValidationException('Error: missing parameters. ', $errors);
        }
    }

}
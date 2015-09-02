<?php

class RestTransactionTest extends PHPUnit_Framework_TestCase
{

    protected $transaction;

    public function setUp()
    {
        $this->transaction = new \Coreproc\Dragonpay\Transaction\RestTransaction(null, true);
    }

    /**
     * @test
     */
    public function it_allows_for_checking_if_the_transaction_result_response_is_valid()
    {
        $actual = $this->transaction->isValid([
            'transactionId'    => '1',
            'referenceNumber'  => 'refno',
            'status'           => 'U',
            'message'          => 'message',
            'digest'           => 'digest',
            'merchantPassword' => 'PASSWORD',
        ]);

        $this->assertFalse($actual);
    }

    /**
     * @test
     */
    public function it_successfully_inquires_for_a_transactions_status()
    {
        $status = $this->transaction->inquire([
            'merchantId'       => 'ID',
            'merchantPassword' => 'PASSWORD',
            'transactionId'    => '12345',
        ]);

        $this->assertEquals('U', $status);
    }

    /**
     * @test
     */
    public function it_allows_for_cancellations_of_a_transaction()
    {
        $status = $this->transaction->cancel([
            'merchantId'       => 'ID',
            'merchantPassword' => 'PASSWORD',
            'transactionId'    => '12345',
        ]);

        $this->assertEquals('-1', $status);
    }

}
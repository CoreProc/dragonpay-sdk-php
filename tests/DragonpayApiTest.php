<?php

use Coreproc\Dragonpay\DragonpayApi;

class DragonpayApiTest extends PHPUnit_Framework_TestCase
{

    protected $dragonpay;

    /**
     * @test
     * @expectedException Exception
     */
    public function it_throws_when_you_dont_supply_a_merchant_id()
    {
        new Coreproc\Dragonpay\DragonpayApi([]);
    }

    /**
     * @test
     * @expectedException Exception
     */
    public function it_throws_when_you_dont_supply_a_merchant_password()
    {
        new Coreproc\Dragonpay\DragonpayApi(['merchantId' => 'ID']);
    }

    /**
     * @test
     * @expectedException Exception
     */
    public function it_throws_when_logging_is_enabled_and_you_dont_provide_a_log_directory()
    {
        new Coreproc\Dragonpay\DragonpayApi([
            'merchantId'       => 'ID',
            'merchantPassword' => 'PASSWORD',
            'logging'          => true
        ]);
    }

    /**
     * @test
     */
    public function it_gets_the_generated_url_to_dragonpay_payment_switch()
    {
        $expected = 'url';

        $generator = Mockery::mock(Coreproc\Dragonpay\UrlGenerator\UrlGeneratorInterface::class);

        $generator->shouldReceive('generate')
            ->with(anything(), anything())
            ->once()
            ->andReturn($expected);

        $dragonpay = new DragonpayApi($this->getMerchantCredentials(), $generator);

        $actual = $dragonpay->getUrl([]);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_logs_the_generation_of_url_to_dragonpay_payment_switch()
    {
        $generator = Mockery::mock(Coreproc\Dragonpay\UrlGenerator\UrlGeneratorInterface::class);

        $generator->shouldReceive('generate')
            ->with(anything(), anything())
            ->once()
            ->andReturn('url');

        $logger = $this->getLoggerMock();

        $logger->shouldReceive('info')->once();

        $dragonpay = new DragonpayApi(
            array_merge($this->getMerchantCredentials(), $this->getLoggingConfig()),
            $generator,
            null,
            $logger
        );

        $actual = $dragonpay->getUrl([]);
    }

    /**
     * @test
     */
    public function it_allows_for_checking_if_the_transaction_result_response_is_valid()
    {
        $transaction = $this->getTransactionInterfaceMock();

        $transaction->shouldReceive('isValid')
            ->with(anything())
            ->once()
            ->andReturn(true);

        $dragonpay = new DragonpayApi($this->getMerchantCredentials(), null, $transaction);

        $actual = $dragonpay->transactionIsValid([]);

        $this->assertTrue($actual);
    }

    /**
     * @test
     */
    public function it_logs_the_checking_of_the_transaction_result_responses_validity()
    {
        $transaction = $this->getTransactionInterfaceMock();

        $transaction->shouldReceive('isValid')
            ->with(anything())
            ->once();

        $logger = $this->getLoggerMock();

        $logger->shouldReceive('info')->once();

        $dragonpay = new DragonpayApi(
            array_merge($this->getMerchantCredentials(), $this->getLoggingConfig()),
            null,
            $transaction,
            $logger
        );

        $dragonpay->transactionIsValid(['transactionId' => 1]);
    }

    /**
     * @test
     */
    public function it_inquires_for_a_transactions_status()
    {
        // S = Success
        // *Refer to "Appendix 3 - Status Codes" (API Docs)
        $expected = 'S';

        $mock = $this->getTransactionInterfaceMock();

        $mock->shouldReceive('inquire')
            ->with(anything())
            ->once()
            ->andReturn($expected);

        $dragonpay = new DragonpayApi($this->getMerchantCredentials(), null, $mock);

        $actual = $dragonpay->inquire([]);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function it_logs_the_inquiry_of_a_transactions_status()
    {
        $transaction = $this->getTransactionInterfaceMock();

        $transaction->shouldReceive('inquire')
            ->with(anything())
            ->once();

        $logger = $this->getLoggerMock();

        $logger->shouldReceive('info')->once();

        $dragonpay = new DragonpayApi(
            array_merge($this->getMerchantCredentials(), $this->getLoggingConfig()),
            null,
            $transaction,
            $logger
        );

        $dragonpay->inquire(['transactionId' => 1]);
    }

    /**
     * @test
     */
    public function it_cancels_transactions()
    {
        // 0 = Success
        // *Refer to "Cancellation of Transaction - Response Parameters" (API Docs)
        $expected = 0;

        $mock = $this->getTransactionInterfaceMock();

        $mock->shouldReceive('cancel')
            ->with(anything())
            ->once()
            ->andReturn($expected);

        $dragonpay = new DragonpayApi($this->getMerchantCredentials(), null, $mock);

        $actual = $dragonpay->cancel([]);

        $this->assertEquals($expected, $actual);
    }

    public function it_logs_the_cancellation_of_a_transaction()
    {
        $transaction = $this->getTransactionInterfaceMock();

        $transaction->shouldReceive('cancel')
            ->with(anything())
            ->once()
            ->andReturn($expected);

        $logger = $this->getLoggerMock();

        $logger->shouldReceive('info')->once();

        $dragonpay = new DragonpayApi(
            array_merge($this->getMerchantCredentials(), $this->getLoggingConfig()),
            null,
            $transaction,
            $logger
        );

        $dragonpay->cancel([]);
    }

    protected function getMerchantCredentials()
    {
        return [
            'merchantId'       => 'ID',
            'merchantPassword' => 'PASSWORD',
        ];
    }

    protected function getLoggingConfig()
    {
        return [
            'logging'      => true,
            'logDirectory' => 'logs'
        ];
    }

    /**
     * @return \Mockery\MockInterface
     */
    protected function getTransactionInterfaceMock()
    {
        return Mockery::mock(Coreproc\Dragonpay\Transaction\TransactionInterface::class);
    }

    /**
     * @return \Mockery\MockInterface
     */
    protected function getLoggerMock()
    {
        return Mockery::mock(Psr\Log\LoggerInterface::class);
    }

    public function tearDown()
    {
        Mockery::close();
    }

}

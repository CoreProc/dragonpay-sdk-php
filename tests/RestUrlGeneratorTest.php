<?php


class RestUrlGeneratorTest extends PHPUnit_Framework_TestCase
{

    protected $generator;

    public function setUp()
    {
        $this->generator = new \Coreproc\Dragonpay\UrlGenerator\RestUrlGenerator(true);
    }

    /**
     * Note: Should we do the generation of the expected value instead of stubbing it?
     *
     * @test
     */
    public function it_generates_the_url_to_dragonpay_payment_switch()
    {
        $actual = $this->generator->generate([
            'merchantId'       => 'ID',
            'merchantPassword' => 'PASSWORD',
            'transactionId'    => '1',
            'amount'           => '1000.00',
            'currency'         => 'PHP',
            'description'      => 'Lorem Ipsum Dolor Amet.',
            'email'            => 'john.doe@email.com',
        ]);

        $expected = 'http://test.dragonpay.ph/Pay.aspx?'
            . 'merchantid=ID'
            . '&txnid=1'
            . '&amount=1000.00'
            . '&ccy=PHP'
            . '&description=Lorem+Ipsum+Dolor+Amet.'
            . '&email=john.doe%40email.com'
            . '&digest=3fe95df2d43b96783df55b94bd6f80d964910fcb';

        $this->assertEquals($expected, $actual);
    }

    /**
     * Note: Do a test for each payment channel type?
     *
     * @test
     */
    public function generation_of_url_allows_for_filtering_by_payment_channel_type()
    {
        $actual = $this->generator->generate([
            'merchantId'       => 'ID',
            'merchantPassword' => 'PASSWORD',
            'transactionId'    => '1',
            'amount'           => '1000.00',
            'currency'         => 'PHP',
            'description'      => 'Lorem Ipsum Dolor Amet.',
            'email'            => 'john.doe@email.com',
        ], 'online_banking');

        $expected = 'http://test.dragonpay.ph/Pay.aspx?'
            . 'merchantid=ID'
            . '&txnid=1'
            . '&amount=1000.00'
            . '&ccy=PHP'
            . '&description=Lorem+Ipsum+Dolor+Amet.'
            . '&email=john.doe%40email.com'
            . '&digest=3fe95df2d43b96783df55b94bd6f80d964910fcb'
            . '&mode=1';

        $this->assertEquals($expected, $actual);
    }

}
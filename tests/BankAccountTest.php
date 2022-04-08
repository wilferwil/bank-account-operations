<?php

use Illuminate\Http\Response;

class BankAccountTest extends TestCase
{
    const EVENT_URI = '/event';
    const BALANCE_URI = '/balance';
    const RESET_URI = '/reset';

    public function testResetTablesEndpoint()
    {
        $request = $this->post(self::RESET_URI);

        $request->assertResponseStatus(Response::HTTP_OK);
    }

    /**
     * Tests balance feature.
     *
     * @return void
     */
    public function testNonExistingAccountBalance()
    {
        $request = $this->get(self::BALANCE_URI . '?account_id=1234');

        $request->assertResponseStatus(Response::HTTP_NOT_FOUND);
    }

    public function testCreateAccountAndDeposit()
    {
        $this->post(self::RESET_URI);
        $request = $this->post(self::EVENT_URI, [
            'type' => 'deposit',
            'destination' => '100',
            'amount' => 10
        ]);

        $request->assertResponseStatus(Response::HTTP_CREATED);
    }

    public function testGetBalanceOfNewAccount()
    {
        $this->post(self::RESET_URI);
        $this->post(self::EVENT_URI, [
            'type' => 'deposit',
            'destination' => '100',
            'amount' => 10
        ]);

        $expected = '10';

        $request = $this->get(self::BALANCE_URI . '?account_id=100');

        $request->assertResponseStatus(Response::HTTP_OK);
        $request->assertSame($expected, $request->response->getContent());
    }

    public function testWithdrawFromNonExistingAccount()
    {
        $request = $this->post(self::EVENT_URI, [
            'type' => 'withdraw',
            'origin' => '200',
            'amount' => 10
        ]);

        $expected = '0';

        $request->assertResponseStatus(Response::HTTP_NOT_FOUND);
        $request->assertSame($expected, $request->response->getContent());
    }

    public function testWithdrawFromExistingAccount()
    {
        $this->post(self::RESET_URI);
        $this->post(self::EVENT_URI, [
            'type' => 'deposit',
            'destination' => '100',
            'amount' => 20
        ]);

        $request = $this->post(self::EVENT_URI, [
            'type' => 'withdraw',
            'origin' => '100',
            'amount' => 5
        ]);

        $expected = '{"origin":{"id":"100","balance":15}}';

        $request->assertResponseStatus(Response::HTTP_CREATED);
        $request->assertSame($expected, $request->response->getContent());
    }

    public function testTransferBetweenAccounts()
    {
        $this->post(self::RESET_URI);
        $this->post(self::EVENT_URI, [
            'type' => 'deposit',
            'destination' => '100',
            'amount' => 15
        ]);

        $request = $this->post(self::EVENT_URI, [
            'type' => 'transfer',
            'origin' => '100',
            'destination' => '300',
            'amount' => 15
        ]);

        $expected = '{"origin":{"id":"100","balance":0},"destination":{"id":"300","balance":15}}';

        $request->assertResponseStatus(Response::HTTP_CREATED);
        $request->assertSame($expected, $request->response->getContent());
    }

    public function testTransferFromNonExistingAccount()
    {
        $request = $this->post(self::EVENT_URI, [
            'type' => 'transfer',
            'origin' => '200',
            'amount' => 15,
            'destination' => '300'
        ]);
    
        $expected = '0';
    
        $request->assertResponseStatus(Response::HTTP_NOT_FOUND);
        $request->assertSame($expected, $request->response->getContent());
    }

    public function testWithdrawOnInsufficientFundsFromExistingAccount()
    {
        $this->post(self::RESET_URI);
        $this->post(self::EVENT_URI, [
            'type' => 'deposit',
            'destination' => '100',
            'amount' => 20
        ]);

        $this->post(self::EVENT_URI, [
            'type' => 'withdraw',
            'origin' => '100',
            'amount' => 20
        ]);
        
        $request = $this->post(self::EVENT_URI, [
            'type' => 'withdraw',
            'origin' => '100',
            'amount' => 150
        ]);
    
        $expected = '0';
    
        $request->assertResponseStatus(Response::HTTP_PAYMENT_REQUIRED);
        $request->assertSame($expected, $request->response->getContent());
    }
}

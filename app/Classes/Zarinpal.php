<?php


namespace App\Classes;


use Exception;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Exceptions\InvoiceNotFoundException;
use Shetabit\Multipay\Invoice;
use Shetabit\Multipay\Payment;

class Zarinpal
{
    /**
     * @var Payment
     */
    private $payment;

    /**
     * @var Invoice
     */
    private $invoice;

    /**
     * @var
     */
    private $txId;

    /**
     * @var
     */
    private $result;

    /**
     * @var
     */
    private $referenceId;

    /**
     * Zarinpal constructor.
     */
    public function __construct()
    {
        $this->payment = new Payment();
        $this->invoice = new Invoice();
    }

    /**
     * @param $amount
     * @return Zarinpal
     * @throws Exception
     */
    public function setAmount($amount): Zarinpal
    {
        $this->invoice->amount($amount);
        return $this;
    }

    /**
     * @param string $description
     * @return Zarinpal
     */
    public function setDescription(string $description): Zarinpal
    {
        $this->invoice->detail(['description' => $description]);
        return $this;
    }

    /**
     * @return Zarinpal
     * @throws Exception
     */
    public function pay(): Zarinpal
    {
        $this->payment->purchase($this->invoice, function ($driver, $txId) {
            $this->txId = $txId;
        })->pay()->render();
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransactionId()
    {
        return $this->txId;
    }

    /**
     * @param $amount
     * @param $txId
     * @return $this
     * @throws InvalidPaymentException
     * @throws InvoiceNotFoundException
     * @throws Exception
     */
    public function verify($amount, $txId): Zarinpal
    {
        try {
            $this->referenceId = $this->payment->amount($amount)->transactionId($txId)->verify()->getReferenceId();
            return $this;
        } catch (Exception $e) {
            if ($e instanceof InvalidPaymentException) {
                throw new InvalidPaymentException($e->getMessage(), 400);
            } else if ($e instanceof InvoiceNotFoundException) {
                throw new InvoiceNotFoundException($e->getMessage(), 400);
            } else {
                throw new Exception($e->getMessage(), 400);
            }
        }
    }

    public function getReferenceId()
    {
        return $this->referenceId;
    }
}

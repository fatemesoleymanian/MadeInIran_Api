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
    protected $payment;

    /**
     * @var Invoice
     */
    protected $invoice;

    /**
     * @var
     */
    protected $txId;

    /**
     * @var
     */
    protected $result;

    /**
     * @var
     */
    protected $referenceId;

    /**
     * Zarinpal constructor.
     */
    public function __construct()
    {
        $this->payment = new Payment();
        $this->invoice = new Invoice();
    }

    /**
     * @return Zarinpal
     */
    public static function service(): Zarinpal
    {
        return new static();
    }

    /**
     * @param $amount
     * @return Zarinpal
     * @throws Exception
     */
    protected function setAmount($amount): Zarinpal
    {
        $this->invoice->amount($amount);
        return $this;
    }

    /**
     * @param string $description
     * @return Zarinpal
     */
    protected function setDescription(string $description): Zarinpal
    {
        $this->invoice->detail(['description' => $description]);
        return $this;
    }

    /**
     * @return Zarinpal
     * @throws Exception
     */
    protected function pay(): Zarinpal
    {
        $this->payment->purchase($this->invoice, function ($driver, $txId) {
            $this->txId = $txId;
        })->pay()->render();
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getTransactionId()
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
    protected function verify($amount, $txId): Zarinpal
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

    protected function getReferenceId()
    {
        return $this->referenceId;
    }
}

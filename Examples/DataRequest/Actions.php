<?php

/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Braspag\Braspag\Examples\DataRequest;

use Braspag\Braspag\Pagador\Transaction\Api\Actions\RequestInterface;

class Actions implements RequestInterface
{
    protected $paymentId;

    public function __construct($paymentId)
    {
        $this->paymentId = $paymentId;
    }

    public function getMerchantId()
    {
        return Auth::MERCHANT_ID;
    }

    public function getMerchantKey()
    {
        return Auth::MERCHANT_KEY;
    }

    public function getPaymentId()
    {
        return $this->paymentId;
    }

    public function getAdditionalRequest()
    {
        return [
            'amount' => 10
        ];
    }
}
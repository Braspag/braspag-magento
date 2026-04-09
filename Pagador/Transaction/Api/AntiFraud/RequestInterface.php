<?php

/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Braspag\Braspag\Pagador\Transaction\Api\AntiFraud;

interface RequestInterface
{
    public function getSequence();

    public function getSequenceCriteria();

    public function getFingerPrintId();

    public function getCaptureOnLowRisk();

    public function getVoidOnHighRisk();

    public function getBrowserCookiesAccepted();

    public function getBrowserEmail();

    public function getBrowserHostName();

    public function getBrowserIpAddress();

    public function getBrowserType();

    public function getCartIsGift();

    public function getCartReturnsAccepted();

    public function getCartItems();

    public function getMerchantDefinedFields();

    public function getCartShippingAddressee();

    public function getCartShippingMethod();

    public function getCartShippingPhone();

    public function getTotalOrderAmount();
}

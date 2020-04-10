<?php
/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */
namespace Webjump\Braspag\Pagador\Transaction\Api\CreditCard\PaymentSplit;

use Webjump\Braspag\Pagador\Transaction\Api\AuthRequestInterface;

interface ResponseInterface
{
    public function getSplits();
}
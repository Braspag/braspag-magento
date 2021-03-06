<?php

/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Braspag\Braspag\Pagador\Transaction\Api\Actions\Void;

interface ResponseInterface
{
    public function getStatus();

    public function getReasonCode();

    public function getReasonMessage();

    public function getProviderReturnCode();

    public function getProviderReturnMessage();

    public function getLinks();
}
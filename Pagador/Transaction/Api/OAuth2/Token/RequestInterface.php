<?php

/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2019 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Braspag\Braspag\Pagador\Transaction\Api\OAuth2\Token;

interface RequestInterface
{
    public function getAccessToken();
}
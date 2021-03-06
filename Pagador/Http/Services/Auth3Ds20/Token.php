<?php

/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2019 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Braspag\Braspag\Pagador\Http\Services\Auth3Ds20;

use Braspag\Braspag\Pagador\Http\Services\ServiceAbstract;
use Braspag\Braspag\Pagador\Http\Services\ServiceInterface;

class Token extends ServiceAbstract implements ServiceInterface
{
    protected $endPoint = '/auth/token/';
}
<?php

/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2016 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 *
 */

namespace Braspag\Braspag\Pagador\Transaction\Resource;

abstract class RequestAbstract
{
    const CONTENT_TYPE_APPLICATION_JSON = 'application/json';
    const CONTENT_TYPE_APPLICATION_X_WWW_FORM_URLENCODED = 'application/x-www-form-urlencoded';

    protected $data;
    protected $params = [];

    abstract protected function prepareParams();

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    public function getData()
    {
        return $this->data;
    }
}
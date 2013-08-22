<?php

namespace Shoplo;

class Checkout extends Resource
{
    public function retrieve($userKey = null, $params = array(), $cache = false)
    {
        if( is_null($userKey) )
        {
            if (!$cache || !isset($this->bucket['carts'])) {
                $params                = $this->prepare_params($params);
                $result                = empty($params) ? $this->send($this->prefix . "checkout") : $this->send($this->prefix . "checkout?" . $params);
                $this->bucket['carts'] = $this->prepare_result($result);
            }
            return $this->bucket['carts'];
        }
        else
        {
            if (!$cache || !isset($this->bucket['carts'][$userKey])) {
                $result                            = $this->send($this->prefix . "/checkout/" . $userKey);
                $this->bucket['carts'][$userKey] = $this->prepare_result($result);
            }
            return $this->bucket['carts'][$userKey];
        }
    }

    public function count($params = array())
    {
        $params = $this->prepare_params($params);
        return $this->send($this->prefix . "checkout/count" . (!empty($params) ? '?' . $params : ''));
    }
}
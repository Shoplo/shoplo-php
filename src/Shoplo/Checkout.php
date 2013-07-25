<?php

namespace Shoplo;

class Checkout extends Resource
{
    public function retrieve($params = array(), $cache = false)
    {
        if (!$cache || !isset($this->bucket['carts'])) {
            $params                = $this->prepare_params($params);
            $result                = empty($params) ? $this->send($this->prefix . "checkout") : $this->send($this->prefix . "checkout?" . $params);
            $this->bucket['carts'] = $this->prepare_result($result);
        }
        return $this->bucket['carts'];
    }

    public function count($params = array())
    {
        $params = $this->prepare_params($params);
        return $this->send($this->prefix . "checkout/count" . (!empty($params) ? '?' . $params : ''));
    }
}
<?php

namespace Shoplo;

class Shop extends Resource
{
    public function retrieve($params = array(), $cache = false)
    {
        if (!$cache || !isset($this->bucket['shop'])) {
            $params                  = $this->prepare_params($params);
            $result                  = empty($params) ? $this->send($this->prefix . "shop") : $this->send($this->prefix . "shop?" . $params);
            $this->bucket['shop']    = $result['shop'];

        }
        return $this->bucket['shop'];

    }
}
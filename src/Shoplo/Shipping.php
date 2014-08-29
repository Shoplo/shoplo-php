<?php

namespace Shoplo;

class Shipping extends Resource
{
    public function retrieve($id = 0, $params = array(), $cache = false)
    {
        if ($id == 0) {
            if (!$cache || !isset($this->bucket['shipping'])) {
                $params                = $this->prepare_params($params);
                $result                = empty($params) ? $this->send($this->prefix . "shipping") : $this->send($this->prefix . "shipping?" . $params);
                $this->bucket['shipping'] = $this->prepare_result($result);
            }
            return $this->bucket['shipping'];
        } else {
            if (!$cache || !isset($this->bucket['shipping'][$id])) {
                $result                     = $this->send($this->prefix . "/shipping/" . $id);
                $this->bucket['shipping'][$id] = $this->prepare_result($result);
            }
            return $this->bucket['shipping'][$id];
        }
    }

    public function count($params = array())
    {
        $params = $this->prepare_params($params);
        return $this->send($this->prefix . "shipping/count" . (!empty($params) ? '?' . $params : ''));
    }

    public function create($fields)
    {
        $fields = array('shipping' => $fields);
        return $this->send($this->prefix . "shipping", 'POST', $fields);
    }

    public function modify($id, $fields)
    {
        $fields = array('shipping' => $fields);
        return $this->send($this->prefix . "shipping/" . $id, 'PUT', $fields);
    }

    public function remove($id)
    {
        return $this->send($this->prefix . "shipping/" . $id, 'DELETE');
    }
}
<?php

namespace Shoplo;

class Promotion extends Resource
{
    public function retrieve($id = 0, $params = array(), $cache = false)
    {
        if ($id == 0) {
            if (!$cache || !isset($this->bucket['promotion'])) {
                $params                = $this->prepare_params($params);
                $result                = empty($params) ? $this->send($this->prefix . "promotions") : $this->send($this->prefix . "promotions?" . $params);
                $this->bucket['promotion'] = $this->prepare_result($result);
            }
            return $this->bucket['promotion'];
        } else {
            if (!$cache || !isset($this->bucket['promotion'][$id])) {
                $result                     = $this->send($this->prefix . "/promotions/" . $id);
                $this->bucket['promotion'][$id] = $this->prepare_result($result);
            }
            return $this->bucket['promotion'][$id];
        }
    }

    public function count($params = array())
    {
        $params = $this->prepare_params($params);
        return $this->send($this->prefix . "promotions/count" . (!empty($params) ? '?' . $params : ''));
    }

    public function create($fields)
    {
        $fields = array('promotion' => $fields);
        return $this->send($this->prefix . "promotions", 'POST', $fields);
    }

    public function modify($id, $fields)
    {
        $fields = array('promotion' => $fields);
        return $this->send($this->prefix . "promotions/" . $id, 'PUT', $fields);
    }

    public function remove($id)
    {
        return $this->send($this->prefix . "promotions/" . $id, 'DELETE');
    }
}
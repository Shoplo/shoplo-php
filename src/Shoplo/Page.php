<?php

namespace Shoplo;

class Page extends Resource
{
    public function retrieve($id = 0, $params = array(), $cache = false)
    {
        if ($id == 0) {
            if (!$cache || !isset($this->bucket['page'])) {
                $params                = $this->prepare_params($params);
                $result                = empty($params) ? $this->send($this->prefix . "pages") : $this->send($this->prefix . "pages?" . $params);
                $this->bucket['page'] = $this->prepare_result($result);
            }
            return $this->bucket['page'];
        } else {
            if (!$cache || !isset($this->bucket['page'][$id])) {
                $result                     = $this->send($this->prefix . "/pages/" . $id);
                $this->bucket['page'][$id] = $this->prepare_result($result);
            }
            return $this->bucket['page'][$id];
        }
    }

    public function count($params = array())
    {
        $params = $this->prepare_params($params);
        return $this->send($this->prefix . "pages/count" . (!empty($params) ? '?' . $params : ''));
    }

    public function create($fields)
    {
        $fields = array('page' => $fields);
        return $this->send($this->prefix . "pages", 'POST', $fields);
    }

    public function modify($id, $fields)
    {
        $fields = array('page' => $fields);
        return $this->send($this->prefix . "pages/" . $id, 'PUT', $fields);
    }

    public function remove($id)
    {
        return $this->send($this->prefix . "pages/" . $id, 'DELETE');
    }
}
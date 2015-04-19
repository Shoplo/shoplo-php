<?php

namespace Shoplo;

class User extends Resource
{
    public function retrieve($id = 0, $params = array(), $cache = false)
    {
        if ($id == 0) {
            if (!$cache || !isset($this->bucket['user'])) {
                $params                   = $this->prepare_params($params);
                $result                   = empty($params) ? $this->send($this->prefix . "users") : $this->send($this->prefix . "users?" . $params);
                $this->bucket['user'] = $this->prepare_result($result);
            }
            return $this->bucket['user'];
        } else {
            if (!$cache || !isset($this->bucket['user'][$id])) {
                $result                        = $this->send($this->prefix . "/users/" . $id);
                $this->bucket['user'][$id] = $this->prepare_result($result);
            }
            return $this->bucket['user'][$id];
        }
    }
}
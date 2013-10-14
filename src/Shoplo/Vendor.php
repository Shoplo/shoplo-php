<?php

namespace Shoplo;

class Vendor extends Resource
{
    public function retrieve($id = 0, $params = array(), $cache = false)
    {
        if ($id == 0) {
            if (!$cache || !isset($this->bucket['vendor'])) {
                $params                   = $this->prepare_params($params);
                $result                   = empty($params) ? $this->send($this->prefix . "vendors") : $this->send($this->prefix . "vendors?" . $params);
                $this->bucket['vendor'] = $this->prepare_result($result);
            }
            return $this->bucket['vendor'];
        } else {
            if (!$cache || !isset($this->bucket['vendor'][$id])) {
                $result                        = $this->send($this->prefix . "/vendors/" . $id);
                $this->bucket['vendor'][$id] = $this->prepare_result($result);
            }
            return $this->bucket['vendor'][$id];
        }
    }

    public function count($params = array())
    {
        $params = $this->prepare_params($params);
        return empty($params) ? $this->send("vendors/count") : $this->send("vendors/count?" . $params);
    }

    public function create($fields)
    {
        $fields = array('vendor' => $fields);
        return $this->send("vendors", 'POST', $fields);
    }

    public function modify($id, $fields)
    {
        $fields = array('vendor' => $fields);
        return $this->send($this->prefix . "vendors/" . $id, 'PUT', $fields);
    }

    public function remove($id)
    {
        return $this->send("vendors/" . $id, 'DELETE');
    }
}
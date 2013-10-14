<?php

namespace Shoplo;

class Collection extends Resource
{
    public function retrieve($id = 0, $params = array(), $cache = false)
    {
        if ($id == 0) {
            if (!$cache || !isset($this->bucket['collection'])) {
                $params                   = $this->prepare_params($params);
                $result                   = empty($params) ? $this->send($this->prefix . "collections") : $this->send($this->prefix . "collections?" . $params);
                $this->bucket['collection'] = $this->prepare_result($result);
            }
            return $this->bucket['collection'];
        } else {
            if (!$cache || !isset($this->bucket['collection'][$id])) {
                $result                        = $this->send($this->prefix . "/collections/" . $id);
                $this->bucket['collection'][$id] = $this->prepare_result($result);
            }
            return $this->bucket['collection'][$id];
        }
    }

    public function count($params = array())
    {
        $params = $this->prepare_params($params);
        return empty($params) ? $this->send("collections/count") : $this->send("collections/count?" . $params);
    }

    public function create($fields)
    {
        $fields = array('collection' => $fields);
        return $this->send("collections", 'POST', $fields);
    }

    public function modify($id, $fields)
    {
        $fields = array('collection' => $fields);
        return $this->send($this->prefix . "collections/" . $id, 'PUT', $fields);
    }

    public function remove($id)
    {
        return $this->send("collections/" . $id, 'DELETE');
    }
}
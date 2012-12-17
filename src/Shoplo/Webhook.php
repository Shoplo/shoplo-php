<?php

namespace Shoplo;

class Webhook extends Resource
{
    public function retrieve($id = 0, $params = array(), $cache = false)
    {
        if ($id == 0) {
            if (!$cache || !isset($this->bucket['webhook'])) {
                $params                   = $this->prepare_params($params);
                $result                   = empty($params) ? $this->send($this->prefix . "webhooks") : $this->send($this->prefix . "webhooks?" . $params);
                $this->bucket['webhook'] = $this->prepare_result($result);
            }
            return $this->bucket['webhook'];
        } else {
            if (!$cache || !isset($this->bucket['webhook'][$id])) {
                $result                        = $this->send($this->prefix . "/webhooks/" . $id);
                $this->bucket['webhook'][$id] = $this->prepare_result($result);
            }
            return $this->bucket['webhook'][$id];
        }
    }

    public function count($params = array())
    {
        $params = $this->prepare_params($params);
        return empty($params) ? $this->send("webhooks/count") : $this->send("webhooks/count?" . $params);
    }

    public function create($fields)
    {
        $fields = array('webhook' => $fields);
        return $this->send("webhooks", 'POST', $fields);
    }

    public function modify($id, $fields)
    {
        $fields = array('webhook' => $fields);
        return $this->send("webhooks/" . $id, 'PUT', $fields);
    }

    public function remove($id)
    {
        return $this->send("webhooks/" . $id, 'DELETE');
    }
}
<?php

namespace Shoplo;

class Order extends Resource
{
	public function retrieve($id = 0, $params = array(), $cache = false)
	{
		if ($id == 0) {
			if (!$cache || !isset($this->bucket['order'])) {
				$params                = $this->prepare_params($params);
				$result                = empty($params) ? $this->send($this->prefix . "orders") : $this->send($this->prefix . "orders?" . $params);
				$this->bucket['order'] = $this->prepare_result($result);
			}
			return $this->bucket['order'];
		} else {
			if (!$cache || !isset($this->bucket['order'][$id])) {
				$result                     = $this->send($this->prefix . "/orders/" . $id);
				$this->bucket['order'][$id] = $this->prepare_result($result);
			}
			return $this->bucket['order'][$id];
		}
	}

	public function count($params = array())
	{
		$params = $this->prepare_params($params);
		return $this->send($this->prefix . "orders/count" . (!empty($params) ? '?' . $params : ''));
	}

	public function create($fields)
	{
		$fields = array('order' => $fields);
		return $this->send("orders", 'POST', $fields);
	}

	public function modify($id, $fields)
	{
        $fields = array('order' => $fields);
        return $this->send($this->prefix . "orders/" . $id, 'PUT', $fields);
	}

	public function remove($id, $params = array())
	{
        $params = $this->prepare_params($params);
        $result = empty($params) ? $this->send($this->prefix . "orders/{$id}/", 'DELETE') : $this->send($this->prefix . "orders/{$id}/?" . $params, 'DELETE');
        return $result;
	}
}
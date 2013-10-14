<?php

namespace Shoplo;

class OrderStatus extends Resource
{
	public function retrieve($id = 0, $params = array(), $cache = false)
	{
		if ($id == 0) {
			if (!$cache || !isset($this->bucket['order_status'])) {
				$params                       = $this->prepare_params($params);
				$result                       = empty($params) ? $this->send($this->prefix . "statuses") : $this->send($this->prefix . "statuses?" . $params);
				$this->bucket['order_status'] = $this->prepare_result($result);
			}
			return $this->bucket['order_status'];
		} else {
			if (!$cache || !isset($this->bucket['order_status'][$id])) {
				$result                            = $this->send($this->prefix . "/statuses/" . $id);
				$this->bucket['order_status'][$id] = $this->prepare_result($result);
			}
			return $this->bucket['order_status'][$id];
		}
	}

	public function count($params = array())
	{
		$params = $this->prepare_params($params);
		return $this->send($this->prefix . "statuses/count" . (!empty($params) ? '?' . $params : ''));
	}

	public function create($fields)
	{
		$fields = array('status' => $fields);
		return $this->send($this->prefix . "statuses", 'POST', $fields);
	}

	public function modify($id, $fields)
	{
		$fields = array('status' => $fields);
		return $this->send($this->prefix . "statuses/" . $id, 'PUT', $fields);
	}

	public function remove($id)
	{
		return $this->send($this->prefix . "statuses/" . $id, 'DELETE');
	}
}
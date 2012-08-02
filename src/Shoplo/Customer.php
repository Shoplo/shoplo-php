<?php

namespace Shoplo;

class Customer extends Resource
{
	public function retrieve($id = 0, $params = array(), $cache = false)
	{
		if ($id == 0) {
			if (!$cache || !isset($this->bucket['customer'])) {
				$params                   = $this->prepare_params($params);
				$result                   = empty($params) ? $this->send($this->prefix . "customers") : $this->send($this->prefix . "customers?" . $params);
				$this->bucket['customer'] = $this->prepare_result($result);
			}
			return $this->bucket['customer'];
		} else {
			if (!$cache || !isset($this->bucket['customer'][$id])) {
				$result                        = $this->send($this->prefix . "/customers/" . $id);
				$this->bucket['customer'][$id] = $this->prepare_result($result);
			}
			return $this->bucket['customer'][$id];
		}
	}

	public function count($params = array())
	{
		$params = url_encode_array($params);
		return sendToAPI($this->prefix . "products/count?" . $params);
	}

	public function create($fields)
	{
		return $this->send($this->prefix . "customers", 'POST', $fields);
	}

	public function modify($id, $fields)
	{
		$fields = array('product' => $fields);
		return sendToAPI($this->prefix . "products/" . $id, 'PUT', $fields);
	}

	public function remove($id)
	{
		return sendToAPI($this->prefix . "products/" . $id, 'DELETE');
	}
}
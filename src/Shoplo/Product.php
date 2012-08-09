<?php

namespace Shoplo;

class Product extends Resource
{
	public function retrieve($id = 0, $collection_id = 0, $category_id = 0, $params = array(), $cache = false)
	{
		if ($id == 0) {
			if (!$cache || !isset($this->bucket['product'])) {
				$params                  = $this->prepare_params($params);
				$result                  = empty($params) ? $this->send($this->prefix . "products") : $this->send($this->prefix . "products?" . $params);
				$this->bucket['product'] = $this->prepare_result($result);
			}
			return $this->bucket['product'];
		} else {
			if (!$cache || !isset($this->bucket['product'][$id])) {
				$result                       = $this->send($this->prefix . "/products/" . $id);
				$this->bucket['product'][$id] = $this->prepare_result($result);
			}
			return $this->bucket['product'][$id];
		}
	}

	public function count($collection_id = 0, $params = array())
	{
		$params = url_encode_array($params);
		return ($collection_id > 0) ? sendToAPI($this->prefix . "products/count?collection_id=" . $collection_id . "&" . $params) : sendToAPI($this->prefix . "products/count?" . $params);
	}

	public function create($fields)
	{
		$fields = array('product' => $fields);
		return sendToAPI($this->prefix . "products", 'POST', $fields);
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
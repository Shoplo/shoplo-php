<?php

namespace Shoplo;

class Category extends Resource
{
	public function retrieve($id = 0, $params = array(), $cache = false)
	{
		if ($id == 0) {
			if (!$cache || !isset($this->bucket['category'])) {
				$params                   = $this->prepare_params($params);
				$result                   = empty($params) ? $this->send($this->prefix . "categories") : $this->send($this->prefix . "categories?" . $params);
				$this->bucket['category'] = $this->prepare_result($result);
			}
			return $this->bucket['category'];
		} else {
			if (!$cache || !isset($this->bucket['category'][$id])) {
				$result                        = $this->send($this->prefix . "/categories/" . $id);
				$this->bucket['category'][$id] = $this->prepare_result($result);
			}
			return $this->bucket['category'][$id];
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
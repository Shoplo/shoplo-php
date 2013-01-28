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

	public function count($params = array())
	{
		$params = $this->prepare_params($params);
		return empty($params) ? $this->send("categories/count") : $this->send("categories/count?" . $params);
	}

	public function create($fields)
	{
        $fields = array('category' => $fields);
        return $this->send("categories", 'POST', $fields);
	}

	public function modify($id, $fields)
	{
        $fields = array('category' => $fields);
        return $this->send($this->prefix . "categories/" . $id, 'PUT', $fields);
	}

	public function remove($id)
	{
		return $this->send("categories/" . $id, 'DELETE');
	}
}
<?php

namespace Shoplo;

class Cart extends Resource
{
	public function retrieve($id = 0, $params = array(), $cache = false)
	{
		if ($id == 0) {
			if (!$cache || !isset($this->bucket['cart'])) {
				$params                   = $this->prepare_params($params);
				$result                   = empty($params) ? $this->send($this->prefix . "carts") : $this->send($this->prefix . "carts?" . $params);
				$this->bucket['cart'] = $this->prepare_result($result);
			}
			return $this->bucket['cart'];
		} else {
			if (!$cache || !isset($this->bucket['cart'][$id])) {
				$result                        = $this->send($this->prefix . "/carts/" . $id);
				$this->bucket['cart'][$id] = $this->prepare_result($result);
			}
			return $this->bucket['cart'][$id];
		}
	}

	public function count($params = array())
	{
		$params = $this->prepare_params($params);
		return empty($params) ? $this->send("cart/count") : $this->send("cart/count?" . $params);
	}

	public function create($fields)
	{
        $fields = array('cart' => $fields);
        return $this->send("carts", 'POST', $fields);
	}

	public function remove($id)
	{
		return $this->send("carts/" . $id, 'DELETE');
	}
}
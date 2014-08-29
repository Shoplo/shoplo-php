<?php

namespace Shoplo;

class Theme extends Resource
{
	public function retrieve($id = 0, $params = array(), $cache = false)
	{
		if ($id == 0) {
			if (!$cache || !isset($this->bucket['theme'])) {
				$params = http_build_query($params);
				$result                       = empty($params) ? $this->send($this->prefix . "themes") : $this->send($this->prefix . "themes?" . $params);
				$this->bucket['theme'] = $this->prepare_result($result);
			}
			return $this->bucket['theme'];
		} else {
			if (!$cache || !isset($this->bucket['theme'][$id])) {
				$result                            = $this->send($this->prefix . "/themes/" . $id);
				$this->bucket['theme'][$id] = $this->prepare_result($result);
			}
			return $this->bucket['theme'][$id];
		}
	}

	public function count($params = array())
	{
		$params = http_build_query($params);
		return $this->send($this->prefix . "themes/count" . (!empty($params) ? '?' . $params : ''));
	}
}
<?php

namespace Shoplo;

class Assets extends Resource
{
	public function retrieve($id = 0, $params = array(), $cache = false)
	{
		if ($id == 0) {
			if (!$cache || !isset($this->bucket['assets'])) {
				$params = http_build_query($params);
				$result = empty($params) ? $this->send($this->prefix . "assets") : $this->send($this->prefix . "assets?" . $params);
				$this->bucket['assets'] = $this->prepare_result($result);
			}
			return $this->bucket['assets'];
		} else {
			if (!$cache || !isset($this->bucket['assets'][$id])) {
				$result                       = $this->send($this->prefix . "/assets/" . $id);
				$this->bucket['assets'][$id] = $this->prepare_result($result);
			}
			return $this->bucket['assets'][$id];
		}
	}

	public function count($params = array())
	{
		$params = http_build_query($params);
		return $this->send($this->prefix . "assets/count?" . $params);
	}

    public function create($fields)
    {
        $fields = array('assets' => $fields);
        return $this->send("assets", 'POST', $fields);
    }

	public function modify($id, $fields)
	{
		$fields = array('assets' => $fields);
		return $this->send($this->prefix . "assets/" . $id, 'POST', $fields);
	}

	public function remove($id)
	{
		return $this->send("assets/" . $id, 'DELETE');
	}
}
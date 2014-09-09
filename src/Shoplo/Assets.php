<?php

namespace Shoplo;

class Assets extends Resource
{
	public function retrieve($themeId, $key = null, $params = array(), $cache = false)
	{
		if (is_null($key))
        {
			if (!$cache || !isset($this->bucket['assets'])) {
				$params = http_build_query($params);
				$result = empty($params) ? $this->send($this->prefix . "themes/{$themeId}/assets") : $this->send($this->prefix . "themes/{$themeId}/assets?" . $params);
				$this->bucket['assets'] = $this->prepare_result($result);
			}
			return $this->bucket['assets'];
		}
        else
        {
			if (!$cache || !isset($this->bucket['assets'][$key])) {
				$result                       = $this->send($this->prefix . "themes/{$themeId}/assets?asset[key]=" . $key);
				$this->bucket['assets'][$key] = $this->prepare_result($result);
			}
			return $this->bucket['assets'][$key];
		}
	}

	public function count($params = array())
	{
		$params = http_build_query($params);
		return $this->send($this->prefix . "assets/count?" . $params);
	}

    public function create($themeId, $fields)
    {
        $fields = array('asset' => $fields);
        return $this->send("themes/{$themeId}/assets", 'POST', $fields);
    }

	public function modify($themeId, $id, $fields)
	{
		$fields = array('asset' => $fields);
		return $this->send($this->prefix . "theme/{$themeId}/assets/" . $id, 'POST', $fields);
	}

	public function remove($themeId, $id)
	{
		return $this->send("themes/{$themeId}/assets?asset[key]=" . $id, 'DELETE');
	}
}
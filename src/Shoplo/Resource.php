<?php

namespace Shoplo;

class Resource
{
	public $prefix = "/";
	protected $bucket = array();

	public function __construct($site)
	{
		$this->prefix = $site . $this->prefix;
	}

	protected function prepare_params($params)
	{
		$string = '';
		if (is_array($params)) {
			foreach ($params as $k => $v) if (!is_array($v)) $string .= $k . '=' . str_replace(' ', '%20', $v) . '&';
			$string = substr($string, 0, strlen($string) - 1);
		}
		return $string;
	}

	protected function prepare_result($result)
	{
		if (!is_array($result)) return array();

		if (isset($result['status'])) return $result;

		/* no organizing needed */
		/*if (!isset($array[$type][0])){
			$temp = $array[$type];
			$id = $temp['id'];
			$array[$type] = array();
			$array[$type][$id] = $temp;
		}else{*/
		$data = array();
		foreach ($result as $k => $v) {
			if (!is_array($v)) {
				$data = $result;
				break;
			}
			$id        = (isset($v['key'])) ? $v['key'] : $v['id'];
			$data[$id] = $v;
			unset($result[$k]);
		}
		/*}*/

		return $data;
	}

	protected function send($url, $request = 'GET', $fields = array())
	{
		$ch   = new objectCURL();
		$data = $ch->send($url, $request, $fields);
		$info = $ch->loadString($data);
		if (isset($info['status']) && $info['status'] == 'err') {
			if ($info['error'] == 202) {
				throw new AuthException($info['error_msg'], $info['error']);
			} else {
				throw new \Exception($info['error_msg'], $info['error']);
			}
		}

		return $info;
	}

	public function __destruct()
	{
		unset($this->prefix);
		unset($this->bucket);
	}
}
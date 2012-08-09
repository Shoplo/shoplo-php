<?php
/*
namespace Shoplo;

class objectCURL
{
	private $ch;
	private $request;

	public function __construct()
	{
		$this->ch = curl_init();
		if (!function_exists('curl_init')) die("Error: cURL does not exist! Please install cURL.");
	}

	public function send($url, $request = 'GET', $fields = false, $headers = array())
	{
		$url           = str_replace('//', '/', $url);
		$url           = str_replace(':/', '://', $url);
		$this->request = $request;

// 		$this->ch = curl_init($url);

		if (GZIP_ENABLED) $headers[] = 'Accept-Encoding: gzip';

		$options = array(
			CURLOPT_URL            => $url,
			CURLOPT_HEADER         => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_CUSTOMREQUEST  => $request,
			CURLOPT_SSL_VERIFYPEER => USE_SSL,
			CURLOPT_HTTPHEADER     => $headers
		);

		//if (USE_SSL_PEM) $options[CURLOPT_CAINFO] = CA_FILE;
		if ($request != "GET" && $request != "POST") $options[CURLOPT_HTTPHEADER] = array('Content-Type: application/json; charset=utf-8');
		if ($fields !== false) {
			$options[CURLOPT_FOLLOWLOCATION] = 1;
			$options[CURLOPT_POST]           = 1;
			$options[CURLOPT_POSTFIELDS]     = http_build_query($fields ? : array());
		}

		curl_setopt_array($this->ch, $options);
		if (!curl_exec($this->ch)) die(curl_error($this->ch));
		$data = (!GZIP_ENABLED) ? curl_multi_getcontent($this->ch) : $this->gzdecode(curl_multi_getcontent($this->ch));
		curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
		curl_close($this->ch);
		return $data;
	}

	public function addParams($params)
	{
		$string = "";
		if ($params) {
			foreach ($params as $param => $value) {
				$string .= $param . '=' . $value . '&';
			}
		}
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $string);
	}

	public function loadString($data)
	{
		if (!function_exists('json_decode')) {
			die("json library not installed. Either change format to .xml or upgrade your version of PHP");
		}

		return json_decode($data, true);
	}

	private function gzdecode($data)
	{
		$g = tempnam(GZIP_PATH, 'ff');
		@file_put_contents($g, $data);
		ob_start();
		readgzfile($g);
		$d = ob_get_clean();
		unlink($g);
		return $d;
	}
}*/
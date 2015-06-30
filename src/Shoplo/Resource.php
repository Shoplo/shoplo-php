<?php

namespace Shoplo;

class Resource
{
    protected $client;
	protected $bucket = array();
    protected $prefix = '';

	public function __construct($client)
	{
        $this->client = $client;
	}

	protected function prepare_params($params)
	{
		$string = '';
		if (is_array($params)) {
			foreach ($params as $k => $v) if (!is_array($v)) $string .= $k . '=' . urlencode($v) . '&';
			$string = substr($string, 0, strlen($string) - 1);
		}
		return $string;
	}

    protected function prepare_result($result)
	{
		if (!is_array($result)) return array();

		if (isset($result['status'])) return $result;

		return $result;

        /*$data = array();
		foreach ($result as $k => $v) {
			if (!is_array($v)) {
				$data = $result;
				break;
			}
			$id        = (isset($v['key'])) ? $v['key'] : $v['id'];
			$data[$id] = $v;
			unset($result[$k]);
		}
		return $data;*/
	}

	protected function send($uri, $request = 'GET', $fields = array())
	{
        if ( 0 === strpos($uri, '/') ) $uri = substr($uri, 1);
        $uri = '/services/'.$uri;

        $method = strtolower($request);
        $headers = null;

        if( $method == 'post')
        {
            $body = $fields;
        }
        elseif( $method == 'put' )
        {
            $body = http_build_query($fields);
        }
        else
        {
            $body = null;
        }

        $response = $this->client->$method($uri, $headers, $body)->send();
        $result = json_decode($response->getBody(true), true);

        if ( isset($result['status']) && $result['status'] == 'err' )
        {
            if ( $result['error'] == '202' ) #Authorize error - need generate new access token
            {
                throw new AuthException($result['error_msg']);
            }
            throw new ShoploException($result['error_msg']);
        }
        return $result;
	}

	public function __destruct()
	{
		unset($this->client);
		unset($this->bucket);
	}
}
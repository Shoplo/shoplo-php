<?php

namespace Shoplo;

class ShoploApi
{
	private $api_key;
	private $secret_key;
	private $url;
	private $redirect_url;
	private $consumer_key;
	private $consumer_secret;

	/**
	 * @var Category
	 */
	public $category;

	/**
	 * @var Collection
	 */
	public $collection;

	/**
	 * @var Customer
	 */
	public $customer;

	/**
	 * @var Order
	 */
	public $order;

	/**
	 * @var OrderStatus
	 */
	public $order_status;

	/**
	 * @var Product
	 */
	public $product;

	/**
	 * @var ProductImage
	 */
	public $product_image;

	/**
	 * @var ProductVariant
	 */
	public $product_variant;

	/**
	 * @var Shop
	 */
	public $shop;

	public function __construct($url, $api_key, $secret_key, $params = array())
	{
		$this->url        = $url;
		$this->api_key    = $api_key;
		$this->secret_key = $secret_key;
		$this->protocol   = 'http';
		$this->url        = $this->prepare_url($this->url);

		if (isset($_SESSION['auth_key'])) {
			$this->setSecretKey($_SESSION['auth_key']);
			$this->instanceModels();
		}
	}

	public function getRedirectUrl()
	{
		return $this->redirect_url;
	}

	public function setRedirectUrl($url)
	{
		$this->redirect_url = $url;
	}

	public function setConsumerKey($consumer_key)
	{
		$this->consumer_key = $consumer_key;
	}

	public function setConsumerSecret($consumer_secret)
	{
		$this->consumer_secret = $consumer_secret;
	}


	// przeniosłem ładowanie modeli później, bo przy tworzeniu tej klasy tak naprawdę nie znamy jeszcze klucza api, którym się będziemy łączyc.


	public function instanceModels()
	{
		if ($this->valid()) {
			$this->category        = new Category($this->siteUrl());
			$this->collection      = new Collection($this->siteUrl());
			$this->customer        = new Customer($this->siteUrl());
			$this->order           = new Order($this->siteUrl());
			$this->order_status    = new OrderStatus($this->siteUrl());
			$this->product         = new Product($this->siteUrl());
			$this->product_image   = new ProductImage($this->siteUrl());
			$this->product_variant = new ProductVariant($this->siteUrl());
			$this->shop            = new Shop($this->siteUrl());
		}
	}

	// analogicznie, jak wyżej.
	public function setApiKey($apiKey)
	{
		$this->api_key = $apiKey;
	}

	public function setSecretKey($secretKey)
	{
		$this->secret_key = $secretKey;
	}

	public function getRequestToken()
	{

		$objectCurl      = new objectCURL();
		$requestTokenUrl = $this->url . '/panel/api/oauth/' . $this->api_key . '/request/token';
		$options         = array(
			'oauth_consumer_key'    => $this->consumer_key,
			'oauth_consumer_secret' => $this->consumer_secret,
			'oauth_url'             => $this->url,
			'oauth_callback'        => $this->redirect_url,
		);
		$objectCurl->addParams($options);
		$data = $objectCurl->send($requestTokenUrl, 'POST');
		$data = json_decode($data);
		header("Location: " . $data->callback . '?requestToken=' . $data->token);
	}

	public function getAccessToken($token)
	{

		$options = array(
			'oauth_consumer_key'    => $this->consumer_key,
			'oauth_consumer_secret' => $this->consumer_secret,
			'oauth_url'             => $this->url,
			'oauth_callback'        => $this->redirect_url,
			'oauth_token'           => $token,
		);

		$objectCurl             = new objectCURL();
		$requestTokenUrl        = $this->url . '/panel/api/oauth/' . $this->api_key . '/access/token/' . $token;
		$options['oauth_token'] = $token;
		$objectCurl->addParams($options);

		$data = $objectCurl->send($requestTokenUrl, 'POST');
		$data = json_decode($data);
		header("Location: " . $data->callback . '?accessToken=' . $data->token);

	}

	public function autorize($data = array())
	{
		if (empty($data['requestToken']) && empty($data['accessToken'])) {
			$this->getRequestToken();
		}

		if (isset($data['requestToken']) && !empty($data['requestToken'])) {
			$requestToken = $_GET['requestToken'];
			$this->getAccessToken($requestToken);
		}

		if (isset($data['accessToken']) && !empty($data['accessToken'])) {
			$accessToken          = $_GET['accessToken'];
			$_SESSION['auth_key'] = $accessToken;
			if (is_string($accessToken)) {
				header('location: ' . $this->getRedirectUrl());
			}
		}
	}

	public function siteUrl()
	{
		#return $this->protocol . '://' . $this->api_key . ':' . $this->computed_password() . '@' . $this->url . '/admin';
		return $this->protocol . '://' . $this->url . '/panel/api/oauth/' . $this->api_key . ':' . $this->computed_password();
	}

	public function valid()
	{
		return ($this->url) ? true : false;
	}

	public function __destruct()
	{
		unset($this->api_key);
		unset($this->secret_key);
		unset($this->url);
		unset($this->category);
		unset($this->collection);
		unset($this->customer);
		unset($this->order);
		unset($this->product);
		unset($this->product_image);
		unset($this->product_variant);
		unset($this->shop);
	}

	/*
		END PUBLIC
		BEGIN PRIVATE
	*/

	private function computed_password()
	{
		return $this->secret_key;
	}

	protected function prepare_url($url)
	{
		return $url;
	}

	public function toString()
	{
		return $this->consumer_key . ' => ' . $this->consumer_secret;
	}
}
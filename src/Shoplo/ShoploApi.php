<?php

namespace Shoplo;
//namespace Guzzle;

define('SHOPLO_API_URL','http://api.shoplo.com');
define('SHOPLO_REQUEST_TOKEN_URI', '/services/oauth/request_token');
define('SHOPLO_ACCESS_TOKEN_URI', '/services/oauth/access_token');
define('SHOPLO_AUTHORIZE_URL', SHOPLO_API_URL . '/services/oauth/authorize');

class ShoploApi
{
    /**
     * @var String
     */
    private $api_key;

    /**
     * @var String
     */
	private $secret_key;

    /**
     * @var ShoploAuthStore
     */
    private $auth_store;

    /**
     * @var String
     */
    private $oauth_token;

    /**
     * @var String
     */
    private $oauth_token_secret;

    /**
     * @var Boolean
     */
    public $authorized = false;

    /**
     * @var Assets
     */
    public $assets;

    /**
	 * @var Cart
	 */
	public $cart;

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

    /**
     * @var Theme
     */
    public $theme;

	/**
	 * @var Webhook
	 */
	public $webhook;

    /**
     * @var Page
     */
    public $page;

    /**
     * @var Checkout
     */
    public $checkout;

	public function __construct($config, $authStore=null, $disableSession=false)
	{
        if ( !$disableSession && !session_id() )
        {
            throw new ShoploException('Session not initialized');
        }
        if ( !isset($config['api_key']) || empty($config['api_key']) )
        {
            throw new ShoploException('Invalid Api Key');
        }
        elseif ( !isset($config['secret_key']) || empty($config['secret_key']) )
        {
            throw new ShoploException('Invalid Api Key');
        }
        elseif ( !isset($config['callback_url']) || empty($config['callback_url']) )
        {
            throw new ShoploException('Invalid Callback Url');
        }


		$this->api_key    = $config['api_key'];
		$this->secret_key = $config['secret_key'];

        $shopDomain = null;
        if( isset($_GET['shop_domain']) )
        {
            $shopDomain = addslashes($_GET['shop_domain']);
            $_SESSION['shop_domain'] = $shopDomain;
        }

        $this->callback_url = (false === strpos($config['callback_url'], 'http')) ? 'http://'.$config['callback_url'] : $config['callback_url'];

        $this->auth_store = AuthStore::getInstance($authStore);

        $this->authorize();


        $client = $this->getClient();
        $this->assets          = new Assets($client);
        $this->category        = new Category($client);
		$this->cart        	   = new Cart($client);
        $this->collection      = new Collection($client);
        $this->customer        = new Customer($client);
        $this->order           = new Order($client);
        $this->order_status    = new OrderStatus($client);
        $this->product         = new Product($client);
        $this->product_image   = new ProductImage($client);
        $this->product_variant = new ProductVariant($client);
        $this->vendor          = new Vendor($client);
        $this->shop            = new Shop($client);
		$this->webhook         = new Webhook($client);
        $this->theme           = new Theme($client);
        $this->page            = new Page($client);
        $this->shipping        = new Shipping($client);
        $this->checkout        = new Checkout($client);
	}


    public function authorize()
    {
        if ( $this->auth_store->authorize() )
        {
            $this->oauth_token        = $this->auth_store->getOAuthToken();
            $this->oauth_token_secret = $this->auth_store->getOAuthTokenSecret();
            $this->authorized         = true;

            return true;
        }

        if ( empty($_GET["oauth_token"]) )
        {
            $this->requestToken();
        }
        else
        {
            $this->accessToken();
        }

        $this->authorized = true;

        return true;
    }

    private function requestToken()
    {
        $client = $this->getClient();
        $response = $client->post(SHOPLO_REQUEST_TOKEN_URI)->send();

        $data = explode('&', $response->getBody(true));
        $token = array();
        foreach ( $data as $d )
        {
            list($k, $v) = explode('=', $d);
            $token[$k] = $v;
        }
        $_SESSION['oauth_token_secret'] = $token['oauth_token_secret'];

        if( isset($_SESSION['shop_domain']) && $_SESSION['shop_domain'] )
        {
            $shopDomain = $_SESSION['shop_domain'];
            $callback_uri = $this->callback_url . '?consumer_key='.rawurlencode($this->api_key).'&shop_domain='.$shopDomain;

            unset($_SESSION['shop_domain']);
        }
        else
            $callback_uri = $this->callback_url . '?consumer_key='.rawurlencode($this->api_key);

        $uri = SHOPLO_AUTHORIZE_URL . '?oauth_token='.rawurlencode($token['oauth_token']).'&oauth_callback='.rawurlencode($callback_uri);

        header('Location: '.$uri);
        exit();
    }

    private function accessToken()
    {
        //  STEP 2:  Get an access token
        $client = $this->getClient($_GET['oauth_token'], $_SESSION['oauth_token_secret']);
        $response = $client->post(SHOPLO_ACCESS_TOKEN_URI)->send();
        unset($_SESSION['oauth_token_secret']);


        $data = explode('&', $response->getBody(true));
        $token = array();
        foreach ( $data as $d )
        {
            list($k, $v) = explode('=', $d);
            $token[$k] = $v;
        }

        $this->oauth_token = $token['oauth_token'];
        $this->oauth_token_secret = $token['oauth_token_secret'];

        $this->auth_store->setAuthorizeData($token['oauth_token'], $token['oauth_token_secret']);
    }

    public function getClient($token=null, $tokenSecret=null)
    {
        $token = !is_null($token) ? $token : ($this->oauth_token ? $this->oauth_token : '');
        $tokenSecret = !is_null($tokenSecret) ? $tokenSecret: ($this->oauth_token_secret ? $this->oauth_token_secret : '');
        $oauth = new \Guzzle\Http\Plugin\OauthPlugin(array(
            'consumer_key'    => $this->api_key,
            'consumer_secret' => $this->secret_key,
            'token'           => $token,
            'token_secret'    => $tokenSecret
        ));
        $client = new \Guzzle\Http\Client(SHOPLO_API_URL);
        $client->addSubscriber($oauth);
        return $client;
    }


	public function getOAuthToken()
    {
        return $this->oauth_token;
    }

    public function getOAuthTokenSecret()
    {
        return $this->oauth_token_secret;
    }

	public function __destruct()
	{
		unset($this->api_key);
		unset($this->secret_key);
		unset($this->oauth_token);
        unset($this->oauth_token_secret);
		unset($this->category);
		unset($this->cart);
		unset($this->collection);
		unset($this->customer);
		unset($this->order);
		unset($this->product);
		unset($this->product_image);
		unset($this->product_variant);
        unset($this->vendor);
		unset($this->shop);
        unset($this->theme);
		unset($this->webhook);
	}
}
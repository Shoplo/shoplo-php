<?php
/**
 * Created by JetBrains PhpStorm.
 * User: grzegorzlech
 * Date: 12-08-08
 * Time: 14:13
 * To change this template use File | Settings | File Templates.
 */

namespace Shoplo\Tests;

class ShoploTest extends \PHPUnit_Framework_TestCase
{
    protected $api_key, $secret_key, $callback_url;

    protected function setUp()
    {
        global $api_key, $secret_key, $callback_url;

        $this->api_key      = $api_key;
        $this->secret_key   = $secret_key;
        $this->callback_url = $callback_url;

        parent::setUp();
    }

    public function testCredentials()
    {
        if (is_null($this->api_key) || is_null($this->secret_key) || is_null($this->callback_url)) {
            $this->markTestSkipped('User credentials missing');
        }
    }

    /**
     * @depends testCredentials
     */
    public function testOAuth()
    {
        $config = array(
            'api_key'      =>  $this->api_key,
            'secret_key'   =>  $this->secret_key,
            'callback_url' =>  $this->callback_url,
        );

        $shoploApi = new \Shoplo\ShoploApi($config);

        $this->assertTrue($shoploApi->authorized);
    }

    /**
     * @depends testOAuth
     */
    public function testProducts()
    {
        $config = array(
            'api_key'      =>  $this->api_key,
            'secret_key'   =>  $this->secret_key,
            'callback_url' =>  $this->callback_url,
        );
        $shoploApi = new \Shoplo\ShoploApi($config);

        $products = $shoploApi->product->retrieve();

        return $products;
    }

    /**
     * @depends testProducts
     */
    public function testResults(array $products)
    {
        $keys = array(
            'id',
            'name',
            'url',
            'description',
            'short_description',
            'delivery_need',
            'availability',
            'tax',
            'add_to_magazine',
            'buy_if_empty',
            'magazine_group',
            'width',
            'height',
            'depth',
            'diameter',
            'weight',
            'thumbnail',
            'is_set',
            'created_at',
            'updated_at',
            'images',
            'variants_in_set',
            'variants'
        );

        foreach ($products as $product) {
            foreach ($keys as $key) {
                $this->assertArrayHasKey($key, $product);
            }
        }
    }
}
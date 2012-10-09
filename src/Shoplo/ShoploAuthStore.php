<?php

namespace Shoplo;

class ShoploAuthStore
{
    static private $instance = false;

    /**
     * Request an instance of the OAuthStore
     */
	public static function getInstance ( $object = null, $options = array() )
    {
        if (!OAuthStore::$instance)
        {
            if ( !($object instanceof ShoploAuthStoreAbstract) )
            {
                OAuthStore::$instance = new ShoploAuthSessionStore();
            }
            else
            {
                OAuthStore::$instance = $object;
            }
        }
        return OAuthStore::$instance;
    }
}
<?php

namespace Shoplo;

class AuthStore
{
    static private $instance = false;

    /**
     * Request an instance of the OAuthStore
     */
	public static function getInstance ( $object = null, $options = array() )
    {
        if (!AuthStore::$instance)
        {
            if ( !($object instanceof AuthStoreAbstract) )
            {
                AuthStore::$instance = new AuthSessionStore();
            }
            else
            {
                AuthStore::$instance = $object;
            }
        }
        elseif ( !is_null($object ) )
        {
            AuthStore::$instance = $object;
        }
        return AuthStore::$instance;
    }
}
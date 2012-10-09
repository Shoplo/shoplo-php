<?php

namespace Shoplo;

/**
 * Created by JetBrains PhpStorm.
 * User: grzegorzlech
 * Date: 12-10-09
 * Time: 08:45
 * To change this template use File | Settings | File Templates.
 */
class AuthSessionStore extends AuthStoreAbstract
{
    private $oauth_token,
            $oauth_token_secret,
            $authorized;

    public function authorize()
    {
        if ( isset($_SESSION['oauth_token']) )
        {
            $this->oauth_token = $_SESSION['oauth_token'];
            $this->oauth_token_secret = $_SESSION['oauth_token_secret'];
            $this->authorized = true;
            return true;
        }
        $this->authorized = false;

        return false;
    }

    public function getOAuthToken()
    {
        return $this->oauth_token;
    }

    public function getOAuthTokenSecret()
    {
        return $this->oauth_token_secret;
    }

    public function setAuthorizeData($oauth_token, $oauth_token_secret)
    {
        $this->oauth_token = $_SESSION['oauth_token'] = $oauth_token;
        $this->oauth_token_secret = $_SESSION['oauth_token_secret'] = $oauth_token_secret;
    }
}
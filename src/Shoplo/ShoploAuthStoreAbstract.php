<?php

namespace Shoplo;

abstract class ShoploAuthStoreAbstract
{
    abstract public function authorize();
    abstract public function getOAuthToken();
    abstract public function getOAuthTokenSecret();
    abstract public function setAuthorizeData($oauth_token, $oauth_token_secret);
}
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: grzegorzlech
 * Date: 12-10-09
 * Time: 08:41
 * To change this template use File | Settings | File Templates.
 */
abstract class ShoploAuthStoreAbstract
{
    abstract public function authorize();
    abstract public function getOAuthToken();
    abstract public function getOAuthTokenSecret();
    abstract public function setAuthorizeData($oauth_token, $oauth_token_secret);
}
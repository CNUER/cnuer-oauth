<?php
namespace cnuer\Oauth;

class OauthCnu
{
    const PREFIX_URL = '';
    const URL_LOGIN = '';
    const URL_GET_ACCESS_TOKEN = '';
    const URL_GET_USER_INFO = '';
    
    private $api_key;
    private $api_secret;
    private $callback_uri;
    private $http_client;
    
    public function __construct($info)
    {
        $this->api_key = $info['api_key'];
        $this->api_secret = $info['api_secret'];
        $this->callback_uri = $info['callback_uri'];
        $this->http_client = new GuzzleHttp\Client();
    }
    
    public function getAccessToken($code)
    {
        $query = [
            'client_id'     => $this->api_key,
            'client_secret' => $this->api_secret,
            'code'          => $code
        ];
        $res = $this->http_client->request('GET', self::PREFIX_URL . self::URL_GET_ACCESS_TOKEN . '?' . http_build_query($query));
        
        if ($res->getStatusCode() == 200) {
            $body = $res->getBody();
            $json = json_decode($body);
            return $json->access_token;
        }
        
        return false;
    }
    
    public function getUserInfo($access_token)
    {
        $query = ['access_token' => $access_token];
        $res = $this->http_client->request('GET', self::PREFIX_URL . self::URL_GET_USER_INFO . '?' . http_build_query($query));
        
        if ($res->getStatusCode() == 200) {
            $body = $res->getBody();
            return json_decode($body);
        }
        
        return false;
    }
    
    public function goToLogin()
    {
        header('location:'. self::PREFIX_URL . self::URL_LOGIN . '?redirect_uri='.urlencode($this->callback_uri));
    }
    
    private function checkKey()
    {
        return !empty($this->api_key) && !empty($this->api_key);
    }
}
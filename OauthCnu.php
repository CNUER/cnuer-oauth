<?php
namespace cnuer\Oauth;

class OauthCnu
{
    const PREFIX_URL = 'https://api.cnuer.cn/oauth2';
    const URL_LOGIN = '/authCnu';
    const URL_GET_ACCESS_TOKEN = '/getAccessToken';
    const URL_GET_USER_INFO = '/getUserInfo';
    
    private $api_key;
    private $api_secret;
    private $callback_uri;
    private $http_client;
    
    public function __construct($info)
    {
        $this->api_key = $info['api_key'];
        $this->api_secret = $info['api_secret'];
        $this->callback_uri = $info['callback_uri'];
        $this->http_client = new \GuzzleHttp\Client();
    }
    
    public function getAccessToken($code)
    {
        $query = [
            'client_id'     => $this->api_key,
            'client_secret' => $this->api_secret,
            'code'          => $code
        ];
        $res = $this->http_client->request('GET', self::PREFIX_URL . self::URL_GET_ACCESS_TOKEN, [
            'query' => $query
        ]);
        
        if ($res->getStatusCode() == 200) {
            $body = $res->getBody();
            return json_decode($body);
        }
        
        return false;
    }
    
    public function getUserInfo($access_token)
    {
        $query = ['access_token' => $access_token];
        $res = $this->http_client->request('GET', self::PREFIX_URL . self::URL_GET_USER_INFO, [
            'query' => $query
        ]);
        
        if ($res->getStatusCode() == 200) {
            $body = $res->getBody();
            return json_decode($body);
        }
        
        return false;
    }
    
    public function goToLogin()
    {
        $query = [
            'client_id'     => $this->api_key,
            'redirect_uri'  => $this->callback_uri
        ];
        header('location:'. self::PREFIX_URL . self::URL_LOGIN . '?'.http_build_query($query));
        exit;
    }
    
    private function checkKey()
    {
        return !empty($this->api_key) && !empty($this->api_key);
    }
}
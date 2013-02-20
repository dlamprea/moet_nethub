<?php
require('client.php');
require('GrantType/IGrantType.php');
require('GrantType/ClientCredentials.php');
const CLIENT_ID     = 'OfJNa844VDadIBqV5L6e';
const CLIENT_SECRET = '145585291457462786241361051250';
const REDIRECT_URI           = '';
//const AUTHORIZATION_ENDPOINT = 'https://api.nethub.co/oauth2/token/';
//const TOKEN_ENDPOINT         = 'https://api.nethub.co/oauth2/token/';
const AUTHORIZATION_ENDPOINT = 'http://192.168.0.24/oauth2/token/';
const TOKEN_ENDPOINT         = 'http://192.168.0.24/oauth2/token/';
$client = new OAuth2\Client(CLIENT_ID, CLIENT_SECRET);
$params = array('code' => '', 'redirect_uri' => REDIRECT_URI);
$response = $client->getAccessToken(TOKEN_ENDPOINT, 'ClientCredentials', array());
//var_dump( $response);
//parse_str($response['result'], $info);
var_dump($response['result']);
$client->setAccessToken($response['result']['access_token']);
$client->setAccessTokenType(1);
//$response = $client->fetch('https://api.nethub.co/stats/consumer/count');
/*$response = $client->fetch('http://192.168.0.24/consumer/', array('limit' => 3,'return'=>'id,last_name,first_name,resource,email','offset'=>4,
'order'=>'first_name,DESC'
));*/
/*$response = $client->fetch('http://192.168.0.24/consumer/', array('order'=>'first_name,DESC'));*/
$response = $client->fetch('http://192.168.0.24/stats/consumer/count');

//var_dump($response, $response['result']);
print("<pre>");
print_r($response);
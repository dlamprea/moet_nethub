<?php
Load::lib('OAuth2/Client');
Load::lib('OAuth2/GrantType/IGrantType');
Load::lib('OAuth2/GrantType/ClientCredentials');

class Marker extends ActiveRecord {
		const CLIENT_ID     			= 'OfJNa844VDadIBqV5L6e';
		const CLIENT_SECRET 			= '145585291457462786241361051250';
		const REDIRECT_URI           	= 'http://url/of/this.php';
		const AUTHORIZATION_ENDPOINT 	= 'https://api.nethub.co/oauth2/token/';
		const TOKEN_ENDPOINT         	= 'https://api.nethub.co/oauth2/token/';
    public function initialize() {
        $this->belongs_to('usopen');
    }
	public function save(){
		$this->save();
		$user = new Usopen();
		$user->find_first($this->usopen_id);
		$id_registro_nethub = $user->nethub_id;
		if(strlen($id_registro_nethub)>= 5){
			//Acceso al token
			$client = new OAuth2\Client(CLIENT_ID, CLIENT_SECRET);
			$params = array('code' => '', 'redirect_uri' => REDIRECT_URI);
			$response = $client->getAccessToken(TOKEN_ENDPOINT, 'ClientCredentials', array());
			$client->setAccessToken($response['result']['access_token']);
			$client->setAccessTokenType(1);
			//Acceso al token
			//Envia los datos de la marca en el usuario de nethub
			$data = array('consume' => array($this->mark => $this->value));
			$response = $client->fetch('https://api.nethub.co/consumer/'.$id_registro_nethub,
			json_encode($data),
			'PUT'
			);
			//Envia los datos de la marca en el usuario de nethub
		}
	}
}
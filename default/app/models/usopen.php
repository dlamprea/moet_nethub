<?php
Load::lib('OAuth2/Client');
Load::lib('OAuth2/GrantType/IGrantType');
Load::lib('OAuth2/GrantType/ClientCredentials');

class Usopen extends ActiveRecord {
		const CLIENT_ID     			= 'OfJNa844VDadIBqV5L6e';
		const CLIENT_SECRET 			= '145585291457462786241361051250';
		const REDIRECT_URI           	= 'http://url/of/this.php';
		const AUTHORIZATION_ENDPOINT 	= 'https://api.nethub.co/oauth2/token/';
		const TOKEN_ENDPOINT         	= 'https://api.nethub.co/oauth2/token/';
    public function initialize() {
        $this->has_many('marker');
    }
	public function create(){
		//Acceso al token
		$client = new OAuth2\Client(CLIENT_ID, CLIENT_SECRET);
		$params = array('code' => '', 'redirect_uri' => REDIRECT_URI);
		$response = $client->getAccessToken(TOKEN_ENDPOINT, 'ClientCredentials', array());
		$client->setAccessToken($response['result']['access_token']);
		$client->setAccessTokenType(1);
		$id_registro_nethub = 0;
		//Acceso al token
		
		//Primero verifica si el usuario se encuentra creado en Nethub
		$response = $client->fetch('https://api.nethub.co/consumer/', array('return'=>'id','email'=>$this->email,'limit'=>'1'));
		if (sizeof($response['result']['data'])>0){
			$id_registro_nethub = $response['result']['data']['0']['id'];
			$data = array("first_name"=>$this->first_name,"last_name"=>$this->last_name,"gender"=>$this->gender,"birthday"=>$this->birthday);
			$update_response = $client->fetch('https://api.nethub.co/consumer/'.$id_registro_nethub,
				json_encode($data,JSON_UNESCAPED_SLASHES),
				'PUT'
				);
		}else{
			try {
				$data = array("first_name"=>$this->first_name,"last_name"=>$this->last_name,"email"=>$this->email,"password"=>md5($this->email),"gender"=>$this->gender,"birthday"=>$this->birthday);
				$create_response = $client->fetch('https://api.nethub.co/consumer/',
					json_encode($data,JSON_UNESCAPED_SLASHES),
					'POST'
					);
				$id_registro_nethub = $create_response['result']['id'];
				} catch (Exception $e) {
					$id_registro_nethub = 0;
				}
		}
		//Primero verifica si el usuario se encuentra creado en Nethub
		
		//Asigna el ID de Nethub a una variable para ser ingresada en la BD
		$this->nethub_id = $id_registro_nethub;
		//Asigna el ID de Nethub a una variable para ser ingresada en la BD
		$this->create();
	}
	public function update(){
		//Acceso al token
		$client = new OAuth2\Client(CLIENT_ID, CLIENT_SECRET);
		$params = array('code' => '', 'redirect_uri' => REDIRECT_URI);
		$response = $client->getAccessToken(TOKEN_ENDPOINT, 'ClientCredentials', array());
		$client->setAccessToken($response['result']['access_token']);
		$client->setAccessTokenType(1);
		$id_registro_nethub = 0;
		//Acceso al token
		
		//Primero verifica si el usuario se encuentra creado en Nethub
		$response = $client->fetch('https://api.nethub.co/consumer/', array('return'=>'id','email'=>$this->email,'limit'=>'1'));
		if (sizeof($response['result']['data'])>0){
			$id_registro_nethub = $response['result']['data']['0']['id'];
			$data = array("first_name"=>$this->first_name,"last_name"=>$this->last_name,"gender"=>$this->gender,"birthday"=>$this->birthday);
			$update_response = $client->fetch('https://api.nethub.co/consumer/'.$id_registro_nethub,
				json_encode($data,JSON_UNESCAPED_SLASHES),
				'PUT'
				);
		}else{
			try {
				$data = array("first_name"=>$this->first_name,"last_name"=>$this->last_name,"email"=>$this->email,"password"=>md5($this->email),"gender"=>$this->gender,"birthday"=>$this->birthday);
				$create_response = $client->fetch('https://api.nethub.co/consumer/',
					json_encode($data,JSON_UNESCAPED_SLASHES),
					'POST'
					);
				$id_registro_nethub = $create_response['result']['id'];
				} catch (Exception $e) {
					$id_registro_nethub = 0;
				}
		}
		//Primero verifica si el usuario se encuentra creado en Nethub
		
		//Asigna el ID de Nethub a una variable para ser ingresada en la BD
		$this->nethub_id = $id_registro_nethub;
		//Asigna el ID de Nethub a una variable para ser ingresada en la BD
		$this->update();
	}
}
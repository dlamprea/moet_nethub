<?php

class LikedFacebook {
    public function parsePageSignedRequest(){
        if (Input::hasRequest('signed_request')) {
            $encoded_sig = null;
            $payload = null;
            list($encoded_sig, $payload) = explode('.',  Input::request('signed_request'), 2);
            $sig = base64_decode(strtr($encoded_sig, '-_', '+/'));
            $data = json_decode(base64_decode(strtr($payload, '-_', '+/'), true));
            return $data;
        }
        return false;
    }
}
<?php
/**
 * Controller por defecto si no se usa el routes
 * 
 */
Load::models('usopen', 'marker');
Load::lib('cookie');
Load::lib('facebook/facebook');

class UsopenController extends AppController{

    var $user_profile = null;

    protected function initialize(){
        $config = Config::get('config.facebook');
        $facebook = new Facebook(array(
            'appId'  => $config['id'],
            'secret' => $config['secret_key']
        ));

        $user = $facebook->getUser();
        $this->user_profile = null;
        if ($user) {
            try {
                $this->user_profile = $facebook->api('/me');
            } catch (FacebookApiException $e) {
                $this->user_profile = null;
            }
        }
        $usopen = new Usopen();
        if(is_null($this->user_profile) === false){
            if(Cookie::get('_ssid_moet')){
                $usopen->find_first(Cookie::get('_ssid_moet'));
            }else{
                $usopen->find_first('facebook_id = ' . $this->user_profile['id']);
            }
            if(is_null($usopen->id)){
                $_user = $this->user_profile;
                $_user['facebook_id'] = $this->user_profile['id'];
                $_user['location_id'] = $this->user_profile['location']['id'];
                $_user['location_name'] = $this->user_profile['location']['name'];
                unset($_user['id']);
                $usopen->create($_user);
            }else{
                $usopen->update();
            }
            Cookie::Set('_ssid_moet', $usopen->id);
        }
        if(!Cookie::get('_ssid_moet')){
            $usopen->save();
        }else{
            $usopen->id = Cookie::get('_ssid_moet');
        }
        Cookie::Set('_ssid_moet', $usopen->id);
    }

    public function index(){
        Load::lib('liked_facebook');
        $fb = new LikedFacebook();
        $this->liked = false;
        if($signed_request = $fb->parsePageSignedRequest()){
            if($signed_request->page->liked){
                $this->liked = true;
                
            } else {
                $this->liked = false;
            }
        }
        $marker =  new Marker();
        $marker->usopen_id = Cookie::Get('_ssid_moet');
        $marker->mark = 'liked';
        $marker->value = $this->liked;
        $marker->save();
    }

    public function user(){
        $create = false;
        if ( Input::isAjax() ){
            if (Input::hasPost('user')){
                View::select(NULL, NULL);
                $user = new Usopen();
                $_user = Input::post('user');
                $id = Filter::get($_user['id'], 'digits');
                if(Cookie::get('_ssid_moet')){
                    $user->find_first(Cookie::get('_ssid_moet'));
                }else{
                    $user->find_first('facebook_id = ' . $this->user_profile['id']);
                }
                if(is_null($user->id)){
                    $_user['facebook_id'] = $_user['id'];
                    $_user['location_id'] = $_user['location']['id'];
                    $_user['location_name'] = $_user['location']['name'];
                    unset($_user['id']);
                    $user->create($_user);
                    $create = true;
                }else{
                    $_user['facebook_id'] = $_user['id'];
                    $_user['id'] = $user->id;
                    $_user['location_id'] = $_user['location']['id'];
                    $_user['location_name'] = $_user['location']['name'];
                    $user->update($_user);
                    $create = true;
                }
                $marker =  new Marker();
                $marker->usopen_id = Cookie::Get('_ssid_moet');
                $marker->mark = 'permissions';
                $marker->value = 1;
                $marker->save();
            }
        }
        echo json_encode(array('success' => true, 'create' => $create));
    }

    public function save(){
        $pic = Input::get('name');
        $pics = array('fireworks', 'stadium', 'trophy');
        View::template(NULL);
        View::select(NULL);
        if(in_array($pic, $pics)){
            $file = dirname(APP_PATH) . '/public/img/' . $pic . '.jpg';
            if (file_exists($file)){
                $marker =  new Marker();
                $marker->usopen_id = Cookie::Get('_ssid_moet');
                $marker->mark = 'cover';
                $marker->value = $pic;
                $marker->save();
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename='.basename($file));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                ob_clean();
                flush();
                readfile($file);
            }
        }
    }

    public function liked(){
        View::select('index');
        $this->liked = true;
    }
    public function choose(){
        $marker =  new Marker();
        $marker->usopen_id = Cookie::Get('_ssid_moet');
        $marker->mark = 'enter_choose';
        $marker->value = 1;
        $marker->save();
    }
    public function cover(){
        $marker =  new Marker();
        $marker->usopen_id = Cookie::Get('_ssid_moet');
        $marker->mark = 'enter_cover';
        $marker->value = 1;
        $marker->save();
    }
    public function game(){
        $marker =  new Marker();
        $marker->usopen_id = Cookie::Get('_ssid_moet');
        $marker->mark = 'enter_game';
        $marker->value = 1;
        $marker->save();
    }
    public function rules(){
        $marker =  new Marker();
        $marker->usopen_id = Cookie::Get('_ssid_moet');
        $marker->mark = 'view_rules';
        $marker->value = 1;
        $marker->save();
    }
    public function share(){
        View::template(NULL);
        View::select(NULL);
        $service = Input::get('service');
        $target = Input::get('target');
        $_service = Filter::get('service', 'alpha');
        $_target = Filter::get('target', 'alpha');
        $marker =  new Marker();
        $marker->usopen_id = Cookie::Get('_ssid_moet');
        $marker->mark = $service;
        $marker->value = $target;
        $marker->save();
    }

    public function gender(){
        View::template(NULL);
        View::select(NULL);
        $user = new Usopen();
        $updates = $user->find("facebook_id IS NOT NULL AND gender = ''");
        foreach($updates as $update){
            $data = json_decode(file_get_contents('https://graph.facebook.com/' . $update->facebook_id));
            if(isset($data->gender)){
                $update->gender = $data->gender;
                $update->update();
            }
        }
    } 
}
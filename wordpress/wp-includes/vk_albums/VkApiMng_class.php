<?php

class VkApiMng{

    private $scope_set = array();
    private $app_id = array();
    private $email = array();
    private $pass = array();
    private $cookies = array();

    private $access_token = array();

    function __construct($scope=array()){
        /**
         * @var $config_values
         */
        include PATH_2_CONFIG."/app.php";
        $app_config = $config_values;
        foreach ($app_config['vk'] as $key=>$item) {
            $this->$key = $item;
        }

        $this->addScope($scope);
        $this->connect();
    }

    public function connect(){
        list($action_url, $formData) = $this->getLoginFormData();
        $location = $this->login($action_url, $formData);
        $this->access_token = $this->getAccessToken($location);
    }

    public function addScope($scope=array()){
        $this->scope_set = array_merge($this->scope_set, $scope);
    }

    private function getLoginFormData(){
        $curlMng = new CurlMng(
            "http://oauth.vk.com/oauth/authorize".
                "?redirect_uri=http://oauth.vk.com/blank.html".
                "&response_type=token".
                "&client_id=".$this->app_id.
                "&display=wap".
                "&scope=".implode(",", $this->scope_set));
        $curlMng->setOptions(array(CURLOPT_RETURNTRANSFER=>true));

        $result = $curlMng->request();
        preg_match('/action=\"([^"]+)\"/', $result, $matches);

        $action_url = $matches[1];
        preg_match_all('/type=\"hidden\"\sname=\"([^"]+)\"\svalue=\"([^"]+)\"/', $result, $matches);

        $formData = array();

        foreach ($matches[1] as $i=>$match_name) {
            $formData[$match_name] = $matches[2][$i];
        }

        return array($action_url, $formData);
    }

    private function login($action_url, $formData){
        $formData['email'] = $this->email;
        $formData['pass'] = $this->pass;


        $curlMng = new CurlMng($action_url);
        $curlMng->setPostMode($formData);
        $curlMng->setOptions(
            array(
                CURLOPT_RETURNTRANSFER=>true,
                CURLOPT_HEADER=>true,
                CURLOPT_FOLLOWLOCATION=>false,
                CURLOPT_SSL_VERIFYPEER=>false,
                CURLOPT_SSL_VERIFYHOST=>false
            ));

        $result = $curlMng->request();

        preg_match('/Location\:\s([^\s]+)/', $result, $matches);

        $location = $matches[1];

        preg_match_all('/Set-Cookie\:\s([^\s]+)/', $result, $matches);
        $this->cookies = $matches[1];

        $curlMng = new CurlMng($location);
        $curlMng->setOptions(
            array(
                CURLOPT_RETURNTRANSFER=>true,
                CURLOPT_HEADER=>true,
                CURLOPT_FOLLOWLOCATION=>false,
                CURLOPT_SSL_VERIFYPEER=>false,
                CURLOPT_SSL_VERIFYHOST=>false
            ));

        $result = $curlMng->request();

        if (preg_match('/Location\:\s([^\s]+)/', $result, $matches)){
            $location = $matches[1];

            preg_match_all('/Set-Cookie\:\s([^\s]+)/', $result, $matches);

            $this->cookies = array_merge($this->cookies, $matches[1]);
        }else{
            preg_match('/action=\"([^"]+)\"/', $result, $matches);
            $action_url = $matches[1];
            $location = $this->acceptGrantAccess($action_url);
        }

        return $location;
    }

    private function acceptGrantAccess($action_url){
        $cookie_str = implode(" ", $this->cookies);

        $curlMng = new CurlMng($action_url);
        $curlMng->setOptions(
            array(
                CURLOPT_RETURNTRANSFER=>true,
                CURLOPT_HEADER=>true,
                CURLOPT_FOLLOWLOCATION=>false,
                CURLOPT_SSL_VERIFYPEER=>false,
                CURLOPT_SSL_VERIFYHOST=>false,
                CURLOPT_COOKIE=>$cookie_str
            ));

        $result = $curlMng->request();

        preg_match_all('/Set-Cookie\:\s([^\s]+)/', $result, $matches);
        $this->cookies = array_merge($this->cookies, $matches[1]);

        preg_match('/Location\:\s([^\s]+)/', $result, $matches);

        return $matches[1];
    }

    private function getAccessToken($location){

        $cookie_str = implode(" ", $this->cookies);
        $curlMng = new CurlMng($location);
        $curlMng->setOptions(
            array(
                CURLOPT_RETURNTRANSFER=>true,
                CURLOPT_HEADER=>true,
                CURLOPT_FOLLOWLOCATION=>false,
                CURLOPT_SSL_VERIFYPEER=>false,
                CURLOPT_SSL_VERIFYHOST=>false,
                CURLOPT_COOKIE=>$cookie_str
            ));

        $result = $curlMng->request();

        preg_match('/access_token=([^&]+)/', $result, $matches);

        $access_token = $matches[1];

        return $access_token;
    }

    public function apiRequest($method, $params=array()){
        $url = "https://api.vk.com/method/$method?access_token=".$this->access_token;
        foreach ($params as $key=>$value) {
            $url .= "&".$key."=".$value;
        }

        $curlMng = new CurlMng($url);
        $curlMng->setOptions(array(
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_FOLLOWLOCATION=>false,
            CURLOPT_SSL_VERIFYPEER=>false,
            CURLOPT_SSL_VERIFYHOST=>false,
        ));

        $request = $curlMng->request();
        $result = json_decode($request, true);

        return isset($result['response']) ? $result['response'] : $result['error'];
    }

    public function processError($data){
        if (isset($data['error_code'])){
            trigger_error($data['error_msg'], E_USER_NOTICE);
        }
    }


    //API wrap

    public function getFullUserName($uid){
        $userData = $this->apiRequest("users.get", array("user_ids"=>$uid));

        return $userData[0]['first_name']." ".$userData[0]['last_name'];
    }

    public function getPostAuthor($post){
        $author_name = '';
        if (isset($post['from_id']) && $post['from_id']>0){
            $author_name = $this->getFullUserName($post['from_id']);
        }elseif(isset($post['signer_id'])){
            $author_name = $this->getFullUserName($post['signer_id']);
        }

        return $author_name;
    }

    public function getWallPostsById($owner_id, $ids){
        foreach ($ids as &$id) {
            $id = $owner_id."_".$id;
        }

        $posts_data = $this->apiRequest("wall.getById", array(
                "posts"=>implode(',', $ids),
                "v"=>"5.5")
        );

        return $posts_data;
    }

    public function getWallPosts($owner_id, $params){
        $total_count = isset($params['limit']) ? $params['limit'] : null;
        $processed = 0;
        $filtered_posts = array();

        $counter=0;
        while(is_null($total_count) || $processed<$total_count){
            $posts_data = $this->apiRequest("wall.get", array(
                    "owner_id"=>$owner_id,
                    "offset"=>100*$counter,
                    "count"=>100,
                    "filter"=>"all",
                    "v"=>"5.5")
            );
            $posts = $posts_data['items'];

            if (is_null($total_count)){
                $total_count = $posts_data['count'];
            }
            $processed += count($posts);
            $counter++;

            $filtered_posts = array_merge($filtered_posts, $posts);

            usleep(200);
        }

        return $filtered_posts;
    }

    public static function parseImgSrcFromAttachmentData($data){
        $prev_property = "";
        $thumb_src = null;
        $src = null;
        foreach ($data as $property_key=>$property_value) {
            if (strpos($property_key, 'photo_')!==false && is_null($thumb_src)){
                $thumb_src = $property_value;
            }
            if (strpos($property_key, 'photo_')===false && !is_null($thumb_src) && is_null($src)){
                $src = $data[$prev_property];
                break;
            }

            $prev_property = $property_key;
        }

        return array($thumb_src, $src);
    }

    public function getAuthorsData($author_ids){
        $authors = array('user'=>array(), 'community'=>array());
        foreach ($author_ids as $author_id) {
            array_push($authors[$author_id>0 ? 'user' : 'community'], $author_id);
        }

        $authorsData = $this->getUsersData($authors['user']);
        $communitiesData = $this->getCommunitiesData($authors['community']);

        foreach ($communitiesData as $community_id=>$community_data) {
            $authorsData["".(0-$community_id)] = $community_data;
        }

        return $authorsData;
    }

    public function getUsersData($ids){
        $total_count = count($ids);
        $processed = 0;
        $packet_size = 1000;

        $counter=0;
        $users_data = array();
        while($processed<$total_count){
            $api_users_data = $this->apiRequest("users.get", array(
                    "user_ids"=>implode(',', array_slice($ids,$counter*$packet_size, $packet_size)),
                    "fields"=>"screen_name,photo_100,photo_max",
                    "v"=>"5.5")
            );

            $users_data = array_merge($users_data, $api_users_data);

            $processed += $packet_size;
            $counter++;

            usleep(200);
        }

        return !empty($users_data) ? Func::arrayReindex($users_data, 'id') : array();
    }

    public function getCommunitiesData($ids){
        $total_count = count($ids);
        $processed = 0;
        $packet_size = 100;

        $counter=0;
        $communities_data = array();
        while($processed<$total_count){
            $api_communities_data = $this->apiRequest("groups.getById", array(
                    "group_ids"=>str_replace("-", "", implode(',', array_slice($ids,$counter*$packet_size, $packet_size))),
                    "fields"=>"id,name,screen_name,photo_100,photo_max",
                    "v"=>"5.5")
            );

            $communities_data = array_merge($communities_data, $api_communities_data);

            $processed += $packet_size;
            $counter++;

            usleep(200);
        }

        return !empty($communities_data) ? Func::arrayReindex($communities_data, 'id') : array();
    }
}

?>
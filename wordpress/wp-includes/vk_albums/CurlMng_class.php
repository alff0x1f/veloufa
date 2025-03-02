<?php

class CurlMng{

    private $curl = null;
    private $options = array();
    private $url = null;

    function __construct($url="", $GET_params=array()){
        $this->setUrl($url, $GET_params);
        $this->init();
    }

    private function getParamsToUrl($url, $params){
        $param_str = "";
        foreach ($params as $param_name=>$param_value) {
            $param_str .= (($param_str=='' && strpos($url, '?')===false) ? '?' : '&')."$param_name=$param_value";
        }

        return $url.$param_str;
    }

    public function setUrl($url="", $GET_params=array()){
        $url = $this->getParamsToUrl($url, $GET_params);
        $this->url = $url;
        return $this;
    }

    public function init(){
        $this->curl = curl_init($this->url);
        return $this;
    }

    public function setPostMode($postData = array()){
        $this->options[CURLOPT_POST] = true;
        if (!empty($postData)){
            $this->options[CURLOPT_POSTFIELDS] = $postData;
        }
    }

    public function setOptions($options){
        if (!is_array($options)){
            $options = array(strval($options)=>true);
        }
        $options_set = array(
            CURL_OPT_SSL_PARSE=>array(
                CURLOPT_RETURNTRANSFER=>true,
                CURLOPT_FOLLOWLOCATION=>false,
                CURLOPT_SSL_VERIFYPEER=>false,
                CURLOPT_SSL_VERIFYHOST=>false
            ),
            CURL_OPT_PARSE=>array(
                CURLOPT_SSL_VERIFYPEER=>false,
                CURLOPT_SSL_VERIFYHOST=>false
            )
        );
        foreach ($options as $key=>$value) {
            if (isset($options_set[$key])){
                foreach ($options_set[$key] as $opt_set_key=>$opt_set_value) {
                    $this->options[$opt_set_key]=$opt_set_value;
                }
            }else{
                $this->options[$key] = $value;
            }
        }
    }

    public function clearOptions(){
        $this->options = array();
        return $this;
    }

    public function jsonRequest(){
        $this->applyOptions();
        $page = $this->execute();

        return json_decode($page, true);
    }

    public function request(){
        $this->applyOptions();
        $page = $this->execute();

        return $page;
    }

    private function applyOptions(){
        foreach ($this->options as $key=>$value) {
            curl_setopt ($this->curl, $key, $value);
        }
    }

    private function execute(){
        $page = curl_exec ($this->curl);
        curl_close ($this->curl);

        return $page;
    }

}

?>
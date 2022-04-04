<?php

class Registry extends Api
{
    public function __construct() {
        //headers
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        header('Access-Contol-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Access-Contol-Allow-Headers, Authorization, X-Requested-With');
        $this->auth = Request::getAuthFromRequest();
    }
    
    public function checkApi($id = 0){

        $this->parseParam($id);
    }

    public function index($id = 0, $params = array()) {
        
        $status = Response::STATUS_BAD_REQUEST;
        $data = array();
        $valid_params = false;

        if(!empty($params)){
            $valid_params = true;
        }

        if($valid_params){
            $status = Response::STATUS_OK;
            if(isset($params['cdn_host'])){
                $data['cdn_host'] = Request::$app['cdn_host'];
            }
            fn_get_response($status, $data);
        } else {
            fn_get_response($status, $data);
        }
    }

    public function create($params = array()) {
        fn_get_response(Response::STATUS_BAD_REQUEST);
    }

    public function update($id, $params = array()) {
        return array();
    }

    public function delete($id) {
        return array();
    }
}

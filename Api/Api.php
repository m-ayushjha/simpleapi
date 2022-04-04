<?php

abstract class Api {

    /**
     * Key of resource name in _REQUEST
     *
     * @const REST_PATH_PARAM_NAME
     */
    const DEFAULT_REQUEST_FORMAT = 'text/plain';

    /**
     * Key of resource name in _REQUEST
     *
     * @const REST_PATH_PARAM_NAME
     */
    const DEFAULT_RESPONSE_FORMAT = 'application/json';

    /**
     * Length of API keys
     *
     * @const API_KEY_LENGTH
     */
    const API_KEY_LENGTH = 32;

    /**
     * Auth data
     * (user => 'user name', api_key => 'API KEY')
     *
     * @var array $auth
     */

    protected $auth;

    abstract public function index($id = '', $params = array());

    abstract public function create($params);

    abstract public function update($id, $params);

    abstract public function delete($id);

    public function parseParam($id)
    {
        $request = new Request();
        $reqHeaders = $request->getHeaders();
        $method = Request::getMethodFromRequestHeaders();
        if($reqHeaders['Content-Type'] == "application/javascript" || $reqHeaders['Content-Type'] == "application/json"){
            $postParam = json_decode(file_get_contents('php://input'), true);
        } else {
            $postParam = $_POST;
        }

        if ($method == "POST") {
            $params = $postParam;
        } elseif ($method == "PUT" || $method == "DELETE"){
            $params = json_decode(file_get_contents('php://input'), true);
        }elseif ($method == "GET") {
            $params = $_GET;
        }
        
        if($method == 'GET') {
            $this->index($id, $params);
        } elseif ($method == 'POST'){
            $this->create($params);
        } elseif ($method == 'PUT') {
            $this->update($id, $params);
        } elseif ($method == 'DELETE'){
            $this->delete($id);
        }
    }
}
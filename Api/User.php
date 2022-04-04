<?php

class User extends Api
{

    public function __construct()
    {
        $this->auth = Request::getAuthFromRequest();
    }

    public function checkApi($id = 0)
    {
        $this->parseParam($id);
    }

    public function index($id = 0, $params = array())
    {
        $status = Response::STATUS_OK;
        $data = [];

        $valid_params = false;
        $data['users'] = [
            [
                'username' => 'Ayush Jha',
                'email' => 'ayush@example.com'
            ],
            [
                'username' => 'John Doe',
                'email' => 'john.doe@example.com'
            ],
            [
                'username' => 'Mickey James',
                'email' => 'mickey.james@example.com'
            ],
        ];

        fn_get_response($status, $data);
    }

    public function create($params = array())
    {
        $status = Response::STATUS_OK;
        $data = array(getenv('USERNAME'));
        $valid_params = false;

        fn_get_response($status, $data);
    }

    public function update($id, $params = array())
    {
        $status = Response::STATUS_BAD_REQUEST;
        $data = array();
        $valid_params = false;

        fn_get_response($status, $data);
    }

    public function delete($id)
    {
        $status = Response::STATUS_BAD_REQUEST;
        $data = array();
        $valid_params = false;
        
        fn_get_response($status, $data);
    }
}

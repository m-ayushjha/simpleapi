<?php

class Request {

    public static $app;
    protected $headers = array();
    protected $content_type;
    protected $accept_type;
    protected $mime_types = array(
        'application/json',
        'application/javascript',
        'multipart/form-data',
        'application/x-www-form-urlencoded',
        'text/plain',
    );

    public function __construct($headers = array()){
        $this->headers = $this->getHeadersFromRequestHeaders();

        if ($this->headers) {
            $this->content_type = $this->getContentTypeFromHeader($this->headers['Content-Type']);
            $this->accept_type = $this->getAcceptTypeFromHeader($this->headers['Accept']);
        }
    }

    public static function db_connect() {
        $conn = Connection::make();
        self::$app['db'] = $conn;
        $sql = "SELECT name, value FROM tk_settings_config";
        $result = $conn->query($sql);
        $config = [];
        if ($result) {
            while ($arr = $result->fetch_assoc()) {
                $config[] = $arr;
            }
            $result->free_result();
        }
        foreach ($config as $conf) {
            self::$app[$conf['name']] = $conf['value'];
        }
    }
    
    public static function getAuthFromRequest()
    {
        $conf = self::$app; // temp method
        $auth = array();
        if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW'])) {
            $api_key = htmlspecialchars($_SERVER['PHP_AUTH_USER']);
            $api_password = htmlspecialchars($_SERVER['PHP_AUTH_PW']);
            $sql = "SELECT user_id, api_key, api_password, user_type, privileges FROM tk_api_users WHERE api_key = '$api_key' AND api_password = '$api_password'";
            $result = db_get_row($sql);

            if(!empty($result)){
                $auth['api_user'] = $result['api_key'];
                $auth['api_key'] = $result['api_password'];
                $auth['user_type'] = $result['user_type'];
                return $auth;
            }
        }
        fn_get_response(Response::STATUS_UNAUTHORIZED);
    }

    public static function getMethodFromRequestHeaders()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    protected function getHeadersFromRequestHeaders(){
        return array(
            'Content-Type' => !empty($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : Api::DEFAULT_REQUEST_FORMAT,
            'Accept'  => !empty($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : Api::DEFAULT_RESPONSE_FORMAT,
        );
    }
    
    public function getHeaders()
    {
        return array(
            'Content-Type' => $this->content_type,
            'Accept'  => $this->accept_type,
        );
    }

    protected function getContentTypeFromHeader($header_content_type)
    {
        $content_type = '';

        if (!empty($header_content_type)) {
            if ($pos_semicolon = strpos($header_content_type, ';')) {
                $content_type = substr($header_content_type, 0, $pos_semicolon);
            } else {
                $content_type = $header_content_type;
            }
        }

        return $content_type;
    }

    protected function getAcceptTypeFromHeader($header_accept)
    {
        $accept_type = '';

        if (!empty($header_accept)) {
            $accept_type = $this->getAvailableContentType(array_keys($this->parseHeaderAccept($header_accept)));
        }

        return $accept_type;
    }

    /**
     * Get the first matching one from the list of the client-requested data types
     *
     * @param array - Data types, sorted by priority
     * @return string Available data type
     */
    protected function getAvailableContentType($mime_types)
    {
        foreach ($mime_types as $type) {
            if (in_array($type, $this->mime_types)) {
                return $type;
            }
            if ($type == '*/*') {
                return Api::DEFAULT_RESPONSE_FORMAT;
            }
        }

        return '';
    }

    /**
     * Splits header Accept line into a data type array
     *
     * @param  string $header Header to parse
     * @return array  Data type array, sorted by priority
     */
    protected function parseHeaderAccept($header)
    {
        if (!$header) {
            return array();
        }

        $types = array();
        $groups = array();
        foreach (explode(',', $header) as $type) {
            // get data type priority
            if (preg_match('/;\s*(q=.*$)/', $type, $match)) {
                $q    = substr(trim($match[1]), 2);
                $type = trim(substr($type, 0, -strlen($match[0])));
            } else {
                $q = 1;
            }

            $groups[$q][] = $type;
        }

        krsort($groups);

        foreach ($groups as $q => $items) {
            $q = (float) $q;

            if (0 < $q) {
                foreach ($items as $type) {
                    $types[trim($type)] = $q;
                }
            }
        }

        return $types;
    }
}
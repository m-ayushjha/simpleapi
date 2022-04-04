<?php

class Router {

	protected $routes = [];

	public static function load($file)
    {
        $router = new static;

        require $file;

        return $router;
    }

    public function define($routes)
    {
        $this->routes = $routes;
    }


    public function direct($uri)
    {
        $uri = explode('?', $uri)[0];
        $key = explode('/', $uri);
        $keys = array_keys($this->routes);
        $isAPI = ($key[0] == 'api')? true : false;
        $key_len = count($key);
        
        $str = $key;
        $api_uri = implode('/', $key);
        array_pop($str);
        $str = implode('/', $str);
        
        if(array_key_exists($uri, $this->routes)){
            return route_segment($uri, $this->routes[$uri]);
        } else {
            $matches = array_values(preg_grep('/'.$key[0].'/', $keys));
            $pattern = array_values(preg_grep('/(:any)/', $matches));
            foreach ($pattern as $p) {
                // For Posts and Pages
                if(strcmp($str, str_replace('/(:any)', '', $p)) === 0){
                    return route_segment($key, $this->routes[$p]);
                } else {
                    fn_get_response(Response::STATUS_NOT_FOUND);
                }
            }
            
            // For API
            if($isAPI){
                $matches = array_values(preg_grep('/api\/'.$key[1].'/', $keys));
                $pattern = array_values(preg_grep('/(:num)/', $matches));
                foreach ($pattern as $p) {
                    if($key_len == 3 && strcmp($str, str_replace('/(:num)', '', $p)) === 0){
                        return route_segment($key, $this->routes[$p], true);
                    }elseif($key_len == 2 && strcmp($api_uri, str_replace('/(:num)', '', $p)) === 0) {
                        return route_segment($key, $this->routes[$p], true);
                    }else {
                        fn_get_response(Response::STATUS_NOT_FOUND);
                    }
                }
            }
            
        }
        // throw new Exception('No route defined for this URI.');
        fn_get_response(Response::STATUS_NOT_FOUND);
    }

}


// public function direct($uri)
//     {
//         if (array_key_exists($uri, $this->routes)) {

//             return $this->routes[$uri];
//         }

//         throw new Exception('No route defined for this URI.');
//     }
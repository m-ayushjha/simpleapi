<?php


/*
*
*  router pattern: 
*  	  key will be the url and 
*     value will the actual route 
*  route rules: 
*     Controller/function/id
*
*  API routing
*     url-pattern should as follows - api/uri/(:num)
*       $id will be the id passed (get, put and delete requests)
*/


$router->define([
    'api/user/(:num)' => 'User',
    'default_controller' => 'Posts/index'
]);

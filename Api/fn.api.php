<?php

/**  return json response
* @param array structure and int
* @return json
*/ 

function fn_get_response($status, $data = array()) {
    if(is_array($data) && is_int($status)){
        // get response codes
        $res = new Response;
        $res = $res->getAvailableCodes();
        if(Response::isSuccessStatus($status)){
            header('Access-Control-Allow-Origin: *');
            header('Content-Type: application/json');
            header('Access-Contol-Allow-Headers: Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, Access-Contol-Allow-Headers, Authorization, X-Requested-With');
            header("status", $res[$status], $status);
            // output json
            echo json_encode($data, JSON_UNESCAPED_SLASHES);
            exit;
        }else {
            header("status", $res[$status], $status);
            if(empty($data)){
                echo json_encode(array('error' => $res[$status]));
            } else {
                echo json_encode($data, JSON_UNESCAPED_SLASHES);
            }
            exit;
        }
    }
}

function fn_custom_api_encode($string,$key) {
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    $hash = '';
    for ($i = 0; $i < $strLen; $i++) {
        $j= 0;
        $ordStr = ord(substr($string,$i,1));
        if ($j == $keyLen) { 
            $j = 0; 
        }
        $ordKey = ord(substr($key,$j,1));
        $j++;
        $hash .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));
    }
    return $hash;
}
function fn_custom_api_decode($string,$key) {
    // echo $string;die;
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    $hash = '';
    for ($i = 0; $i < $strLen; $i+=2) {
        $j = 0;
        $ordStr = hexdec(base_convert(strrev(substr($string,$i,2)),36,16));
        if ($j == $keyLen) { $j = 0; }
        $ordKey = ord(substr($key,$j,1));
        $j++;
        $hash .= chr($ordStr - $ordKey);
    }
    return $hash;
}
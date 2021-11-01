<?php
        
    function getIP(){
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        $ip_array = explode(",", $ip);
        $ip = trim($ip_array[0]);
        return $ip;
    }

    function custom_utf8($d) {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = custom_utf8($v);
            }
        } else if (is_string ($d)) {
            return utf8_encode($d);
        }
        return $d;
    }
    /** 
     * get header Authorization
     * */
    function getAuthorizationHeader(){
            $headers = null;
            if (isset($_SERVER['Authorization'])) {
                $headers = trim($_SERVER["Authorization"]);
            }
            else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
                $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
            } elseif (function_exists('apache_request_headers')) {
                $requestHeaders = apache_request_headers();
                $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
                if (isset($requestHeaders['Authorization'])) {
                    $headers = trim($requestHeaders['Authorization']);
                }
            }
            return $headers;
    }
    /*
     * get access token from header
     *
     */
    function getBearerToken() {
        $headers = getAuthorizationHeader();
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }


    function validateContent($method ='', &$json_decoded){
        if($_SERVER['REQUEST_METHOD'] != $method){
            echo json_encode(array('nCode'=>405,
                            'sMessage'=> 'HTTP/1.1 405 Accion Not Allowed. Se espera '.$method.' en el Metodo'));
            exit();
        }
        $MGeneral['Content_typeInvalid'] = "";
        $content_type = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : null;
        $content_type = strcasecmp($content_type, 'application/json') != 0 ? 'Se espera content_type: application/json' : null;

        $json_content = json_decode(trim(file_get_contents("php://input")),true);
        $json_decoded = $json_content;
        $json_content = !is_array($json_content) ? 'El json no es valido.' : null;
        
        if($content_type != null || $json_content != null){
            $oResult = array('content_type' =>$content_type, 'json_content'=>$json_content);
            echo json_encode($oResult);
            exit();
        }
    }
    

?>
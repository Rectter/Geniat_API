<?php
use Firebase\JWT\JWT;

class AuthJWT
{
    private static $secret_key = '@G3n14t2021';
    private static $encrypt = ['HS256'];
    private static $aud = null;

    public static function Sign($data)
    {
        $token = array(
            'exp' => time() + (1800),
            'aud' => self::getAud(),
            'data' => $data
        );

        return JWT::encode($token, self::$secret_key);
    }

    public static function getData($token)
    {
            return JWT::decode(
                $token,
                self::$secret_key,
                self::$encrypt
            )->data;
    }

    private static function getAud()
    {
        $aud = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }
        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();
        return sha1($aud);
    }
}
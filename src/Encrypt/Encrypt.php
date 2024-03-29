<?php

namespace Weigot\Tools\Encrypt;

use Weigot\Tools\Encrypt\Request\SignRequest;

class Encrypt
{

    private static $instance;

    private function __construct()
    {

    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public $key = "ab123ced23qefgHIJqweKlmnOPQ44rst";

    public function encrypt($string, $operation, $key = '')
    {
        empty($key) && $key = $this->key;
        $src = array("/", "+", "=");
        $dist = array("_a", "_b", "_c");
        if ($operation == 'D') {
            $string = str_replace($dist, $src, $string);
        }
        $key = md5($key);
        $key_length = strlen($key);
        $string = $operation == 'D' ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
        $string_length = strlen($string);
        $rndkey = $box = array();
        $result = '';
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($key[$i % $key_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'D') {
            if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
                return substr($result, 8);
            } else {
                return '';
            }
        } else {
            $rdate = str_replace('=', '', base64_encode($result));
            $rdate = str_replace($src, $dist, $rdate);
            return $rdate;
        }
    }

    /**
     * 加密
     * @param String $str 要加密的数据
     * @param string $key
     * @return false|string 加密后的数据
     */
    public function aesEncrypt($str, $key = "")
    {
        empty($key) && $key = $this->key;
        return openssl_encrypt($str, 'AES-256-ECB', $this->key);
    }

    /**
     * 解密
     * @param $str
     * @return false|string
     */
    public function aesDecrypt($str)
    {
        empty($key) && $key = $this->key;
        return openssl_decrypt($str, 'AES-256-ECB', $this->key);
    }

    /**
     * 获取签名
     * @param SignRequest $signRequest
     * @return string
     * @throws \Weigot\Tools\Exception\WGException
     */
    public function getSign(SignRequest $signRequest)
    {
        $signRequest->validate();
        $params = $signRequest->getParams();
        $params = array_unique($params);
        if ($signRequest->getParamsSort() == 'ksort') {
            ksort($params);
        } else {
            asort($params);
        }
        if ($signRequest->getKey()) {
            $params["_key"] = $signRequest->getKey();
        }
        $hyphen = $signRequest->getHyphen();
        if (strtolower($hyphen) == 'json') {
            $str = json_encode($params);
        } else {
            $str = implode($hyphen, $params);
        }
        switch ($signRequest->getEncryptMethod()) {
            default:
            case "md5":
                $sign = md5($str);
                break;
            case 'sha256':
                $sign = hash("sha256", $str);
                break;
        }
        return $sign;
    }
}
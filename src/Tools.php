<?php

namespace Weigot\Tools;

use Weigot\Tools\Binary\Binary;
use Weigot\Tools\Encrypt\Encrypt;
use Weigot\Tools\Encrypt\Request\SignRequest;

class Tools
{
    /**
     * 格式化树形结构
     * @param $list
     * @param string $pidKey
     * @param string $idKey
     * @param string $childKey
     * @return array
     */
    public static function TreeList($list, $pidKey = "parent_id", $idKey = "id", $childKey = "children")
    {
        $formatList = [];
        foreach ($list as $id => $item) {
            if (isset($item[$pidKey]) &&
                isset($list[$item[$pidKey]])
            ) {
                $list[$item[$pidKey]][$childKey][] = &$list[$item[$idKey]];
            } else {
                $formatList[] = &$list[$item[$idKey]];
            }
        }
        return $formatList;
    }

    /**
     * @param $string
     * @param $operation {D:解码；E:加密}
     * @param string $key
     * @return mixed|string
     */
    public static function Encrypt($string, $operation, $key = '')
    {
        $encrypt = Encrypt::getInstance();
        return $encrypt->encrypt($string, $operation, $key);
    }

    /**
     * @param array $array
     * @param bool $field
     * @return array
     */
    public static function _usort(array $array, $field = false)
    {
        function build_sorter($key)
        {
            return function ($a, $b) use ($key) {
                if ($key && isset($a[$key])) {
                    return strnatcmp($a[$key], $b[$key]);
                } else {
                    return strnatcmp($a, $b);
                }
            };
        }

        usort($array, build_sorter($field));
        return $array;
    }

    public static function _uasort(array $array, $field = false)
    {
        uasort($array, function () use ($field) {
            return function ($a, $b) use ($field) {
                if ($field && isset($a[$field])) {
                    return strnatcmp($a[$field], $b[$field]);
                } else {
                    return strnatcmp($a, $b);
                }
            };
        });
        return $array;
    }

    /**
     * 生成guid
     * @return string
     */
    public static function CreateGuid()
    {
        $now = microtime();
        $charid = strtoupper(md5(uniqid(mt_rand(), true) . $now));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12);
        return $uuid;
    }

    /**
     * 获得301或者302跳转的真实地址
     * @param $url
     * @return mixed
     */
    public static function GetRealUrl($url)
    {
        $header = get_headers($url, 1);
        if (strpos($header[0], '301') || strpos($header[0], '302')) {
            $url = $header['location'];
            if (is_array($header['location'])) {
                $url = $header['location'][count($header['location']) - 1];
            }
        }
        return $url;
    }

    /**
     * 生成一个数字串
     * @param int $length
     * @return string
     */
    public static function GenerateNumber($length = 8)
    {
        $date = trim(date('Ymdhis ', time()));
        $connt = 0;
        $temp = array();
        while ($connt < $length) {
            $temp[] = mt_rand(0, 9);
            $data = array_flip(array_flip($temp));
            $connt = count($data);
        }
        shuffle($data);
        $str = implode(",", $data);
        $number = str_replace(',', '', $str);
        return $date . $number;
    }

    /**
     * 获取config
     * @param $path
     * @return Config
     */
    public static function Config($path)
    {
        return new Config($path);
    }

    /**
     * 统计二进制中1出现的次数
     * @param $num
     * @return int
     */
    public static function countOneBits($num)
    {
        $binary = self::Binary();
        return $binary->countOneBits($num);
    }

    /**
     * 文件递归查询
     * @param $path
     * @param null $callback
     */
    public static function folderFile($path, $callback = null)
    {
        $dh = opendir($path);
        while (($d = readdir($dh)) != false) {
            if ($d == '.' || $d == '..') {
                continue;
            }
            $folder = $path . "/" . $d;
            if ($callback) {
                call_user_func($callback, $folder);
            }
            if (is_dir($folder)) {
                self::folderFile($folder, $callback);
            }
        }
    }

    /**
     * 获取签名
     * @param SignRequest $signRequest
     * @return string
     * @throws Exception\WGException
     */
    public static function getSign(SignRequest $signRequest)
    {
        $encrypt = Encrypt::getInstance();
        return $encrypt->getSign($signRequest);
    }

    /**
     * 二进制类
     * @return Binary
     */
    public static function Binary()
    {
        return Binary::getInstance();
    }
}
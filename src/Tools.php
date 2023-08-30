<?php

namespace Weigot\Tools;

use Weigot\Tools\Binary\Binary;
use Weigot\Tools\Encrypt\Encrypt;
use Weigot\Tools\Encrypt\Request\SignRequest;
use Weigot\Tools\Exception\WGException;

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
     * @param string $hyphen
     * @param array $limits
     * @return string
     * @throws WGException
     */
    public static function CreateGuid($hyphen = "-", $limits = [8, 4, 4, 4, 12])
    {
        if (array_sum($limits) > 32) {
            throw new WGException("截取范围超出限值");
        }
        $now = microtime();
        $charId = strtoupper(md5(uniqid(mt_rand(), true) . $now));
        if (empty($hyphen)) {
            return $charId;
        }
        $subData = [];
        $limits = array_values($limits);
        foreach ($limits as $key => $limit) {
            if ($key == 0) {
                $start = 0;
            } else {
                $start = $limits[$key - 1] + (empty($limits[$key - 2]) ? 0 : $limits[$key - 2]);
            }
            $subData[] = substr($charId, $start, $limit);
        }
        return implode($hyphen, $subData);
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
        $count = 0;
        $temp = array();
        while ($count < $length) {
            $temp[] = mt_rand(0, 9);
            // 如果长度小于10就去重
            if ($count < 10) {
                $data = array_flip(array_flip($temp));
            } else {
                $data[] = end($temp);
            }
            $count = count($data);
        }
        // 重新排列数组
        shuffle($data);
        $str = implode(",", $data);
        return str_replace(',', '', $str);
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
<?php

namespace Weigot\Tools;

use Weigot\Tools\Encrypt\Encrypt;

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
     * @param bool|false $field
     * @param bool|false $index 是否保留原来的索引
     * @return array
     */
    public static function _usort(array $array, $field = false, $index = false)
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

        $index ? uasort($array, build_sorter($field)) : usort($array, build_sorter($field));
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
}
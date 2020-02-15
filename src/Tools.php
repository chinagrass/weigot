<?php
namespace Weigot\Tools;

use Weigot\Tools\Encrypt\Encrypt;

class Tools
{
    /**
     * 格式化树形结构，同时保证数据中含有id和parent_id
     * @param $list
     * @return array
     */
    public static function TreeList($list)
    {
        $formatList = [];
        foreach ($list as $id => $item) {
            if (isset($item["parent_id"]) &&
                isset($list[$item['parent_id']])
            ) {
                $list[$item['parent_id']]['children'][] = &$list[$item['id']];
            } else {
                $formatList[] = &$list[$item['id']];
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
}
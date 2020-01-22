<?php
namespace WeiGot\Tools;

use WeiGot\Tools\Encrypt\Encrypt;

class Tools
{
    /**
     * 格式话树形结构，同时保证数据中含有id和parent_id
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
                $formatList = &$list[$item['id']];
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
}
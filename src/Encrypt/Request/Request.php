<?php


namespace Weigot\Tools\Encrypt\Request;


use Weigot\Tools\Exception\WGException;

class Request
{
    public function validate()
    {
        $vars = get_object_vars($this);
        foreach ($vars as $k => $v) {
            if (empty($v)) {
                throw new WGException("{$k} is required");
            }
        }
    }
}
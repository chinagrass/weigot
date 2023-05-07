<?php


namespace Weigot\Tools\Encrypt\Request;


class SignRequest extends Request
{
    protected $params;
    protected $encryptMethod = 'md5';
    protected $hyphen;
    protected $key;
    protected $paramsSort = "ksort";

    /**
     * @return string
     */
    public function getParamsSort()
    {
        return $this->paramsSort;
    }

    /**
     * @param $paramsSort
     */
    public function setParamsSort($paramsSort)
    {
        $this->paramsSort = $paramsSort;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getEncryptMethod()
    {
        return $this->encryptMethod;
    }

    /**
     * @param $encryptMethod
     */
    public function setEncryptMethod($encryptMethod)
    {
        $this->encryptMethod = strtolower($encryptMethod);
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return mixed
     */
    public function getHyphen()
    {
        return $this->hyphen;
    }

    /**
     * @param $hyphen
     */
    public function setHyphen($hyphen)
    {
        $this->hyphen = $hyphen;
    }
}
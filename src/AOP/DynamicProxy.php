<?php

namespace Weigot\Tools\AOP;

use Weigot\Tools\Exception\AOPException;
use Weigot\Tools\Tools;

class DynamicProxy
{
    protected $object;

    /**
     * DynamicProxy constructor.
     * @param object $object
     */
    public function __construct(object $object)
    {
        $this->object = $object;
    }

    /**
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws AOPException
     */
    public function __call($name, $arguments)
    {
        $object = $this->getObject();
        if (!method_exists($object, $name)) {
            $objectName = get_class($object);
            throw new AOPException("{$objectName} 对象不存在 {$name} 方法");
        }
        try {
            $result = $this->execute($name, $arguments);
        } catch (AOPException $e) {
            $result = $this->runMetod($object, $name, $arguments);
        }
        return $result;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    private function execute($name, $arguments)
    {
        $object = $this->getObject();
        $interceptors = $this->getInterceptors($object, $name);
        switch (true) {
            case empty($interceptors):
                $interceptors = [];
                break;
            case is_string($interceptors):
                $interceptors = [
                    $interceptors
                ];
                break;
        }
        $interceptorObjs = [];
        foreach ($interceptors as $key => $interceptor) {
            $interceptorObj = new $interceptor();
            // 如果实例化的对象不是 Interceptor 就跳过
            if (!($interceptorObj instanceof Interceptor)) {
                continue;
            }
            $interceptorObjs[] = $interceptorObj;
        }
        foreach ($interceptorObjs as $interceptorObj) {
            $this->runMethod($interceptorObj, "before", $arguments);
        }
        $result = $this->runMethod($object, $name, $arguments);
        // 之后执行，做一个反转
        $interceptorObjs = array_reverse($interceptorObjs);
        foreach ($interceptorObjs as $interceptorObj) {
            $this->runMethod($interceptorObj, "after", $result);
        }
        return $result;
    }

    /**
     * @param $object
     * @param $method
     * @return array
     */
    private function getInterceptors($object, $method)
    {
        $interceptors = [];
        switch (true) {
            case is_callable([$object, 'getInterceptors']):
                $interceptors = $object->getInterceptors();
                break;
            case !empty(get_object_vars($object)["interceptors"]):
                $interceptors = $object->interceptors;
                break;
            default:
                $class = get_class($object);
                $interceptors = !isset(Tools::Config("config")["aop"][$class][$method]) ? Tools::Config("config")["aop"][$class][$method] : [];
                break;
        }
        return $interceptors;
    }

    /**
     * @param $obj
     * @param $method
     * @param $params
     * @return mixed
     */
    private function runMethod($obj, $method, $params)
    {
        if (is_array($params)) {
            $result = call_user_func_array([$obj, $method], $params);
        } else {
            $result = call_user_func([$obj, $method], $params);
        }
        return $result;
    }
}

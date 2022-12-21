<?php

namespace Weigot\Tools\AOP;

use Weigot\Tools\Exception\AOPException;

class DynamicProxy
{
    protected $object;

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
            $result = $this->interceptorRun($name, $arguments);
        } catch (AOPException $e) {
            $result = call_user_func_array([$object, $name], $arguments);
        }
        return $result;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws AOPException
     */
    private function interceptorRun($name, $arguments)
    {
        $object = $this->getObject();
        switch (true) {
            case method_exists($object, "getInterceptors"):
                $interceptors = $object->getInterceptors();
                break;
            case property_exists($object, 'interceptors'):
                $interceptors = $object->interceptors;
                break;
            default:
                throw new AOPException("切入类没有配置");
                break;
        }
        if (is_string($interceptors)) {
            $interceptors = [
                $interceptors
            ];
        }
        $interceptorObjs = [];
        foreach ($interceptors as $interceptor) {
            $interceptorObj = new $interceptor();
            // 如果实例化的对象不是 Interceptor 就跳过
            if (!($interceptorObj instanceof Interceptor)) {
                continue;
            }
            $interceptorObjs[] = $interceptorObj;
        }
        foreach ($interceptorObjs as $interceptorObj) {
            call_user_func_array([$interceptorObj, 'before'], $arguments);
        }
        $result = call_user_func_array([$object, $name], $arguments);
        // 之后执行，做一个反转
        $interceptorObjs = array_reverse($interceptorObjs);
        foreach ($interceptorObjs as $interceptorObj) {
            call_user_func_array([$interceptorObj, 'after'], $result);
        }
        return $result;
    }
}

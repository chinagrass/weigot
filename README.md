# WeiGot

#### 介绍
项目开发中经常会用到一些方法，所以在这里做了一个整合。同时集成了一些便捷化操作的策略。

#### 软件架构
```
weigot
├─src  # 应用
│  ├─AOP # AOP使用
│  ├─Date  # 时间工具
│  │  ├─ Date.php
│  │  └─ Time.php
│  ├─Encrypt  # 加密解密工具
│  ├─Exception  # 异常类
│  ├─Office  # 静态资源文件
│  │    ├─ Excel.php  # excel类
│  │    ├─ IExcel.php  # 接口
│  │    └─ ExcelTypeEnum.php  # excel分类枚举
│  └─Tools.php  # 工具类
├─README.md  # 文档说明
└─composer.json
```

#### 安装教程
```
composer require weigot/tools
```

#### 使用说明

##### 1. 获取树形结构
```
Tools::TreeList($list);
```
##### 2. 使用AOP
###### 2.1 切入类需要继承Interceptor，例如
```
class LogService extends Interceptor {
    public function before(...$data){
        // todo someting ...
    }
    public function after(...$data){
        // todo someting ...
    }
}
```
###### 2.2 被切入的对象，需要设置属性$interceptors，具体格式为
```
public $interceptors = [
    InterceptorService::class // 切入类
];
```
###### 或者，
```
use InterceptorTrait;
protected $interceptors = [
    InterceptorService::class // 切入类
];
```
##### 或者，
```$xslt
# 在项目的根目录下，增加config目录，并在目录下编写aop.php文件，具体格式为
<?php
return [
    TestService::class=>[
        'method'=>[
            InterceptorService::class,
        ],
    ],
];
```

##### 3. office使用
```
参考tests中的单元测试实例
```

##### 4. Tools下的方法
```$xslt
# 获取树形结构
Tools：：TreeList($list, $pidKey, $idKey, $childKey);
# 加密解密
Tools：：Encrypt($string, $operation, $key)；
# 不保留原键排序
Tools：：_usort($array, $field);
# 保留原键排序
Tools：：_uasort($array, $field);
# 生成guid
Tools：：CreateGuid();
# 获得301或者302跳转的真实地址
Tools：：GetRealUrl($url);
# 自定义字符串转大写
Tools：：strToUpper($str);
# 生成一个数字串
Tools：：GenerateNumber($length);
# 获取config
Tools：：Config($path);
# 统计二进制中1出现的次数
Tools：：countOneBits($num);
# 文件递归查询
Tools：：folderFile($path, $callback);
```
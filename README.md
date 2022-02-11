# weigot

#### 介绍
项目开发常用的一些通用的公共方法的整合

#### 软件架构
```
weigot
├─src  # 应用
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
└─composer.json  # 迁移文件记录
```

#### 安装教程
```
composer require weigot/tools
```

#### 使用说明
```
// 获取树形结构

Tools::TreeList($list);
```

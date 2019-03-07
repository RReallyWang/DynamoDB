# DynamoDB操作封装
像使用MySQL一样使用DynamoDB(Use DynamoDB as you would with MySQL)  
禁止使用保留字，具体哪些是保留字可在包文件 DynamoDb.php 文件中查看，如果已经使用保留字的用户，请读下一条  
保留字问题已在内部处理，使用者可以不用关心保留字问题（AWS不支持的地方此工具同样不支持，AWS支持的地方此工具已内部处理）  


## 目录
#### 1. [安装](#1)
#### 2. [使用](#2)
#### 3. [新增数据](#3)
#### 4. [修改数据](#4)
#### 5. [查询数据](#5)
#### 6. [删除数据](#6)
#### 7. [获取数量](#7)
#### 8. [部分删除](#8)
#### 9. [自增与自减](#9)

## 现在，开始

### <span id = "1">1. 安装</span>

```
composer require reallywang/dynamo-db
```
或是composer.json中require下添加
```
"reallywang/dynamo-db": "^1.3"
```

### <span id = "2">2. 使用</span>
```
require_once "vendor/autoload.php";

use ReallyWang\DynamoDB\DB;

// 整理配置
$config = [
    'default' => [
        'region' => '',
        'version' => '',
        'credentials' => [
            'key' => '',
            'secret' => ''
        ]
    ],
    'test' => [
        'region' => '',
        'version' => '',
        'credentials' => [
            'key' => '',
            'secret' => ''
        ]
    ]
];

// 获取连接对象
DB::config($config); // 默认为default
// DB::config($config, 'test'); // 使用test数据库配置
```
### <span id = "3">3. 新增数据</span>
```
// 向wrtest表中新增数据
$result = DB::table('wrtest')->insert(['id' => 1, 'detail' => '23123']);
```
### <span id = "4">4. 修改数据</span>
```
// 修改wrtest表中 id = 123 且detail 大于 2的行 detail 为 23123
$result = DB::table('wrtest')->key(['id' => '123'])->condition(['detail' => ['>', 2]])->update(['detail' => '23123']);
```
### <span id = "5">5. 查询数据</span>
```
// 查询wrtest表中id = 123的数据（id 必须是主键，find方法必须与key方法同时使用）
$result = DB::table('wrtest')->key(['id' => '123'])->find();

// 查询wrtest表中id = 1 且 detail 大于 2 的数据中的detail属性，condition 中必须包括主键
$result = DB::table('wrtest')->condition(['id' => 1, 'detail' => ['>', 2]])->field(['detail'])->get();

// 查询wrtest表中detail 小于 0 或 detail 在 2 和 3 之间的数据
$result = DB::table('wrtest')->condition(['detail' => ['<', 0]])->orCondition(['detail' => ['between', 2, 3]])->scan();
```
### <span id = "6">6. 删除数据</span>
```
// 删除wrtest表中 id = 123 且detail 大于 2的行
$result = DB::table('wrtest')->key(['id' => '123'])->condition(['detail' => ['>', 2]])->delete();
```
### <span id = "7">7. 获取数量</span>
```
// 获取wrtest表中 id = 123 且detail 大于 2的数据条数
$result = DB::table('wrtest')->key(['id' => '123'])->condition(['detail' => ['>', 2]])->count();
```
### <span id = "8">8. 部分删除</span>
```
// 修改wrtest表中 id = 123 且detail 大于 2的行 detail 为 23123
$result = DB::table('wrtest')->key(['id' => '123'])->condition(['detail' => ['>', 2]])->remove(['detail.author[0]']);

// 将wrtest表中 id = 123 且detail.author 中有3个元素 的行 detail.author中的第一个元素删掉
$result = DB::table('wrtest')->key(['id' => '123'])->condition(['detail.author' => ['size', 3]])->remove(['detail.author[0]']);

// 将wrtest表中 id = 123 且detail.author 中元素数量大于等于3 的行 detail.author中的第一个元素删掉
$result = DB::table('wrtest')->key(['id' => '123'])->condition(['detail.author' => ['size', '>=', 3]])->remove(['detail.author[0]']);

// 将wrtest表中 id = 123 且detail.author 中元素个数在3和10之间 的行 detail.author中的第一个元素删掉
$result = DB::table('wrtest')->key(['id' => '123'])->condition(['detail.author' => ['size', 'between', 3, 10]])->remove(['detail.author[0]']);
```
### <span id = "9">9. 自增与自减</span>
```
// wrtest表 符合 id 为123 的行 num属性会递增 1
$result = DB::table('wrtest')->key(['id' => '123'])->condition(['detail' => '123'])->step('num');

// wrtest表 符合 id 为123 的行 num属性会递减 2
$result = DB::table('wrtest')->key(['id' => '123'])->condition(['detail' => '123'])->step('num', false, 2);
```

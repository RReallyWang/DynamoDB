# DynamoDB操作封装
像使用MySQL一样使用DynamoDB(Use DynamoDB as you would with MySQL)

## 目录
#### 1. [安装](#1)
#### 2. [使用](#2)
#### 3. [新增数据](#3)
#### 4. [修改数据](#4)
#### 5. [查询数据](#5)
#### 6. [删除数据](#6)
#### 7. [获取错误](#7)
#### 8. [获取数量](#8)
#### 9. [部分删除](#9)
#### 10. [自增与自减](#10)

## 现在，开始

### <span id = "1">1. 安装</span>

```
composer require reallywang/dynamo-db
```
或是composer.json中require下添加
```
"reallywang/dynamo-db": "^1.0"
```

### <span id = "2">2. 使用</span>
```
require_once "vendor/autoload.php";

use ReallyWang\DynamoDB\DynamoDB;

// 整理配置
$config = [
    'region' => '',
    'version' => '',
    'credentials' => [
        'key' => '',
        'secret' => ''
    ]
];

// 获取连接对象
$db = new DynamoDB($config);
```
### <span id = "3">3. 新增数据</span>
```
// 向wrtest表中新增数据
$result = $db->table('wrtest')->insert(['id' => 1, 'detail' => '23123']);
```
### <span id = "4">4. 修改数据</span>
```
// 修改wrtest表中 id = 123 且detail 大于 2的行 detail 为 23123
$result = $db->table('wrtest')->key(['id' => '123'])->condition(['detail' => ['>', 2]])->update(['detail' => '23123']);
```
### <span id = "5">5. 查询数据</span>
```
// 查询wrtest表中id = 123的数据（id 必须是主键，find方法必须与key方法同时使用）
$result = $db->table('wrtest')->key(['id' => '123'])->find();

// 查询wrtest表中id = 1 且 detail 大于 2 的数据中的detail属性，condition 中必须包括主键
$result = $db->table('wrtest')->condition(['id' => 1, 'detail' => ['>', 2]])->field(['detail'])->get();

// 查询wrtest表中detail 小于 0 或 detail 在 2 和 3 之间的数据
$result = $db->table('wrtest')->condition(['detail' => ['<', 0]])->orCondition(['detail' => ['between', 2, 3]])->scan();
```
### <span id = "6">6. 删除数据</span>
```
// 删除wrtest表中 id = 123 且detail 大于 2的行
$result = $db->table('wrtest')->key(['id' => '123'])->condition(['detail' => ['>', 2]])->delete();
```
### <span id = "7">7. 获取错误</span>
```
$result = $db->table('wrtest')->find();
// 上面这条查询会返回null，因为没有使用key传入主键
// 获取报错信息可以使用getError方法
$error = $db->getError();
var_dump($error);
die();
```
### <span id = "8">8. 获取数量</span>
```
// 获取wrtest表中 id = 123 且detail 大于 2的数据条数
$result = $db->table('wrtest')->key(['id' => '123'])->condition(['detail' => ['>', 2]])->count();
```
### <span id = "9">9. 部分删除</span>
```
// 修改wrtest表中 id = 123 且detail 大于 2的行 detail 为 23123
$result = $db->table('wrtest')->key(['id' => '123'])->condition(['detail' => ['>', 2]])->remove(['detail.author[0]']);

// 将wrtest表中 id = 123 且detail.author 中有3个元素 的行 detail.author中的第一个元素删掉
$result = $db->table('wrtest')->key(['id' => '123'])->condition(['detail.author' => ['size', 3]])->remove(['detail.author[0]']);

// 将wrtest表中 id = 123 且detail.author 中元素数量大于等于3 的行 detail.author中的第一个元素删掉
$result = $db->table('wrtest')->key(['id' => '123'])->condition(['detail.author' => ['size', '>=', 3]])->remove(['detail.author[0]']);

// 将wrtest表中 id = 123 且detail.author 中元素个数在3和10之间 的行 detail.author中的第一个元素删掉
$result = $db->table('wrtest')->key(['id' => '123'])->condition(['detail.author' => ['size', 'between', 3, 10]])->remove(['detail.author[0]']);
```
### <span id = "10">10. 自增与自减</span>
```
// wrtest表 符合 id 为123 的行 num属性会递增 1
$result = $db->table('wrtest')->key(['id' => '123'])->condition(['detail' => '123'])->step('num');

// wrtest表 符合 id 为123 的行 num属性会递减 2
$result = $db->table('wrtest')->key(['id' => '123'])->condition(['detail' => '123'])->step('num', false, 2);
```

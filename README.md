QH4框架扩展模块-签到模块

该模块支持用户连续签到,循环签到,指定签到间隔时间.

<span style="color: #f03c15"> 注意：该模块只处理签到逻辑,不负责具体业务逻辑(例如增加用户积分) </span>

用户应该重写扩展类中的 `afterHandle`  方法实现具体业务

### 关于签到配置
模块提供了一份配置表,表中包含了很多预留字段,这些字段模块中没有使用. 也就是,实际业务中,可以只使用一个预留字段,其它的删除.也可以增加其它字段来满足业需求.

因为配置表影响到实际业务逻辑,所以模块也没有提供配置表的增删改查


### api列表
```php
actionSignin()
```
用户签到接口,只负责处理用户签到逻辑,签到后具体执行的业务(比如增加积分等)并未涉及


### 方法列表
```php
/**
 * 获取某个用户最后一次签到记录
 * @param string $user_id
 * @param ExtSignin $external
 * @param DbModel $db
 * @return array
 */
public static function getLastSignin($user_id = null, ExtSignin $external = null, $db = null)
```
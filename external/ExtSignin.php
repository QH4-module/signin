<?php
/**
 * File Name: ExtSignin.php
 * ©2020 All right reserved Qiaotongtianxia Network Technology Co., Ltd.
 * @author: hyunsu
 * @date: 2021/5/6 9:55 上午
 * @email: hyunsu@foxmail.com
 * @description:
 * @version: 1.0.0
 * ============================= 版本修正历史记录 ==========================
 * 版 本:          修改时间:          修改人:
 * 修改内容:
 *      //
 */

namespace qh4module\signin\external;


use qttx\components\db\DbModel;
use qttx\helper\TimeHelper;
use qttx\web\External;

defined('SIGNIN_MODE_CONTINUED') or define('SIGNIN_MODE_CONTINUED', 'continued');
defined('SIGNIN_MODE_LOOP') or define('SIGNIN_MODE_LOOP', 'loop');

/**
 * Class ExtSignin
 * 用户签到的扩展类
 * @package qh4module\signin\external
 */
class ExtSignin extends External
{
    /**
     * @return string 签到表名
     */
    public function tableName()
    {
        return '{{%user_signin}}';
    }

    /**
     * @return string 签到配置表名
     */
    public function confTableName()
    {
        return '{{%signin_conf}}';
    }

    /**
     * 签到的模式
     * SIGNIN_MODE_CONTINUED:  连续签到
     *      假设设置了7天的连续签到, 用户在第7次以后连续签到的时候,会一直作为第7次计算
     * SIGNIN_MODE_LOOP: 循环签到
     *      假设设置了7天的连续签到,用户在第8次签到的时候,会重新作为第1次签到计算
     * @return string
     */
    public function mode()
    {
        return SIGNIN_MODE_LOOP;
    }

    /**
     * 连续签到的间隔时间
     * 一般间隔时间分为2种: 固定间隔, 按照自然时间间隔(日,周,月)
     * @param $prev_time int 上次签到的时间
     * @return int 需要返回一个秒级时间戳
     */
    public function maxInterval($prev_time)
    {
        // 实例1: 固定1小时间隔
//        return $prev_time + 3600;

        // 实例2: 按照日,自然时间间隔(最常用的每日签到)
        // 即:今天签到后,到明天的23:59:59再次签到,都是连续签到
        return TimeHelper::dayLaterTime('23:59:59', 1, $prev_time);

        // 实例3: 按照周签到,即今天签到,到下周日的23:59:59都算是,连续签到
//        $stamp = TimeHelper::weekLaterTime(0, 2, $prev_time);
//        return strtotime(date('Y-m-d 23:59:59', $stamp));
    }

    /**
     * 签到最小间隔时间
     * 即在返回的时间前,不能再次签到
     * @param $prev_time int 上次签到的时间
     * @return int 返回一个秒级时间戳
     */
    public function minInterval($prev_time)
    {
        // 实例1: 每小时签到一次,即当前小时的59:59前不能再次签到
//        return strtotime(date('Y-m-d H:59:59', $prev_time));

        // 实例2: 按照日,自然时间间隔(最常用的每日签到)
        // 即:今天签到后,到今天的23:59:59不能再次签到
        return strtotime(date('Y-m-d 23:59:59', $prev_time));

        // 实例3: 按照周签到,即今天签到,到这个周日的23:59:59都不能再次签到
//        $stamp = TimeHelper::weekLaterTime(0, 1, $prev_time);
//        return strtotime(date('Y-m-d 23:59:59', $stamp));
    }

    /**
     * 重复签到的提示语
     * @return string
     */
    public function repeatText()
    {
        return '您已经签到过了，不要来捣乱(:';
    }

    /**
     * 签到成功后执行,这里面是具体的业务逻辑,比如增加用户经验等
     * @param array $result_sign 新插入的签到表的数据
     * @param array|null $result_conf 对应本次签到配置的数据,逻辑是获取满足当前连续签到次数最近的一条配置
     *         比如,连续签到第5次, 如果配置表有对应的第5次配置,则取这一条
     *              如果没有对应的,则顺序寻找,连续签到4次,连续签到3次这样的配置
     *         所以当配置表,最小连续签到次数为6次的时候,这个参数为空
     * @param DbModel $db 该函数会被包裹在一个事务中,函数中执行的sql务必使用该句柄
     */
    public function afterHandle($result_sign, $result_conf, $db)
    {
        // todo 这里写具体的业务逻辑
        var_dump($result_sign);
        var_dump($result_conf);

//        // 伪代码:
//        if (!empty($result_conf)) {
//            // 增加用户经验
//            $user->balance += $result_conf['balance'];
//            // 增加用户积分
//            $user->scores += $result_conf['scores'];
//            $user->update($db);
//        }
//
    }
}
<?php
/**
 * File Name: HpSignin.php
 * ©2020 All right reserved Qiaotongtianxia Network Technology Co., Ltd.
 * @author: hyunsu
 * @date: 2021/5/6 3:04 下午
 * @email: hyunsu@foxmail.com
 * @description:
 * @version: 1.0.0
 * ============================= 版本修正历史记录 ==========================
 * 版 本:          修改时间:          修改人:
 * 修改内容:
 *      //
 */

namespace qh4module\signin;


use qh4module\signin\external\ExtSignin;
use qh4module\token\TokenFilter;
use QTTX;
use qttx\components\db\DbModel;

class HpSignin
{
    /**
     * 获取某个用户最后一次签到记录
     * @param string $user_id
     * @param ExtSignin $external
     * @param DbModel $db
     * @return array
     */
    public static function getLastSignin($user_id = null, ExtSignin $external = null, $db = null)
    {
        if (is_null($user_id)) $user_id = TokenFilter::getPayload('user_id');
        if (is_null($external)) $external = new ExtSignin();
        if (is_null($db)) $db = $external->getDb();

        return $db->select('*')
            ->from($external->tableName())
            ->whereArray(['user_id' => $user_id])
            ->orderByDESC(['create_time'])
            ->row();
    }
}
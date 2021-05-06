<?php
/**
 * File Name: Signin.php
 * ©2020 All right reserved Qiaotongtianxia Network Technology Co., Ltd.
 * @author: hyunsu
 * @date: 2021/5/6 2:09 下午
 * @email: hyunsu@foxmail.com
 * @description:
 * @version: 1.0.0
 * ============================= 版本修正历史记录 ==========================
 * 版 本:          修改时间:          修改人:
 * 修改内容:
 *      //
 */

namespace qh4module\signin\models;


use qh4module\signin\external\ExtSignin;
use qh4module\signin\HpSignin;
use qh4module\token\TokenFilter;
use QTTX;
use qttx\exceptions\InvalidConfigException;
use qttx\web\ServiceModel;

class Signin extends ServiceModel
{
    /**
     * @var ExtSignin
     */
    protected $external;

    /**
     * @inheritDoc
     */
    public function run()
    {
        $user_id = TokenFilter::getPayload('user_id');

        $db = $this->external->getDb();

        $db->beginTrans();

        try {

            // 获取最后一次签到记录
            $result_last = HpSignin::getLastSignin($user_id, $this->external, $db);
            if ($result_last && time() < $this->external->minInterval($result_last['create_time'])) {
                $db->rollBackTrans();
                $this->addError('user_id', $this->external->repeatText());
                return false;
            }

            $cols = $this->getCols($user_id, $result_last);
            // 插入签到表
            $db->insert($this->external->tableName())
                ->cols($cols)
                ->query();
            // 查找对应配置
            $result_conf = QTTX::$app->db->select('*')
                ->from($this->external->confTableName())
                ->where('keep_num <= :num and del_time=0')
                ->bindValue('num', $cols['keep_num'])
                ->orderByDESC(['keep_num'])
                ->row();

            // 执行用户逻辑
            $this->external->afterHandle($cols, $result_conf, $db);

            $db->commitTrans();

            return true;

        } catch (\Exception $exception) {
            $db->rollBackTrans();
            throw $exception;
        }
    }

    protected function getCols($user_id, $result_last)
    {
        // 插入数据库的字段
        $cols = [
            'id' => QTTX::$app->snowflake->id(),
            'user_id' => $user_id,
            'create_time' => time(),
        ];
        // 计算是否是连续签到
        if (!empty($result_last) && time() <= $this->external->maxInterval($result_last['create_time'])) {
            // 连续签到
            $cols['is_keep'] = 1;
            $cols['keep_num'] = $result_last['keep_num'] + 1;
            $cols['real_keep_num'] = $result_last['real_keep_num'] + 1;
            $cols['total_num'] = $result_last['total_num'] + 1;
            // 查找最大连续签到次数
            $max_num = QTTX::$app->db
                ->select('max(keep_num)')
                ->from($this->external->confTableName())
                ->where('del_time=0')
                ->single();
            if (!$max_num) {
                throw new InvalidConfigException('Sign in configuration cannot be empty!');
            }
            // 根据模式计算连续次数
            if ($cols['keep_num'] > $max_num) {
                switch ($this->external->mode()) {
                    case SIGNIN_MODE_LOOP:
                        $cols['keep_num'] = 1;
                        break;
                    case SIGNIN_MODE_CONTINUED:
                        $cols['keep_num'] = $max_num;
                        break;
                    default:
                        throw new InvalidConfigException('Invalid sign in mode!');
                }
            }
        } else {
            // 非连续签到
            $cols['is_keep'] = 0;
            $cols['keep_num'] = 1;
            $cols['real_keep_num'] = 1;
            $cols['total_num'] = empty($result_last) ? 1 : $result_last['total_num'] + 1;
        }

        return $cols;
    }

}
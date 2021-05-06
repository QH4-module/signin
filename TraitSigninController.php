<?php
/**
 * File Name: TraitSigninController.php
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

namespace qh4module\signin;


use qh4module\signin\external\ExtSignin;
use qh4module\signin\models\Signin;

/**
 * Trait TraitSigninController
 * 用户签到模块,只负责签到操作,不负责具体业务
 * @package qh4module\signin
 */
trait TraitSigninController
{
    /**
     * 扩展类
     * @return ExtSignin
     */
    public function ext_signin()
    {
        return new ExtSignin();
    }

    /**
     * 用户签到接口
     * 该模块只负责处理用户签到逻辑,签到后具体执行的业务(比如增加积分等)并未涉及
     * @see ExtSignin::afterHandle() 重写该方法实现具体业务逻辑
     */
    public function actionSignin()
    {
        $model = new Signin([
            'external' => $this->ext_signin(),
        ]);

        return $this->runModel($model);
    }

}
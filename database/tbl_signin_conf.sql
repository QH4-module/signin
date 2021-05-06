DROP TABLE IF EXISTS `tbl_signin_conf`;

# 这个表所有预留字段,可以随着业务更改或自定义,模块中不会使用这些字段
CREATE TABLE IF NOT EXISTS `tbl_signin_conf`
(
    `id`          INT UNSIGNED   NOT NULL AUTO_INCREMENT,
    `keep_num`    INT            NOT NULL COMMENT '连续签到次数',
    `balance`     DECIMAL(10, 2) NULL COMMENT '预留字段,增加余额',
    `scores`      DECIMAL(10, 2) NULL COMMENT '预留字段,增加积分',
    `reward1`     DECIMAL(10, 2) NULL COMMENT '预留字段,奖励1',
    `reward2`     DECIMAL(10, 2) NULL COMMENT '预留字段,奖励2',
    `reward3`     DECIMAL(10, 3) NULL COMMENT '预留字段,奖励3',
    `create_time` BIGINT         NOT NULL,
    `del_time`    BIGINT         NOT NULL,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB
    COMMENT = '签到配置表';
DROP TABLE IF EXISTS `tbl_user_signin`;

CREATE TABLE IF NOT EXISTS `tbl_user_signin`
(
    `id`            VARCHAR(64) NOT NULL,
    `user_id`       VARCHAR(64) NOT NULL COMMENT '用户',
    `create_time`   BIGINT      NOT NULL COMMENT '签到时间',
    `is_keep`       TINYINT     NOT NULL COMMENT '是否是连续签到',
    `keep_num`      INT         NOT NULL COMMENT '连续签到次数,根据配置,可能被重置为1',
    `real_keep_num` INT         NOT NULL COMMENT '实际连续签到次数',
    `total_num`     INT         NOT NULL,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB
    COMMENT = '用户-签到';

CREATE INDEX `fk_tbl_user_signin_tbl_user1_idx` ON `tbl_user_signin` (`user_id` ASC);

CREATE INDEX `create_time_index` ON `tbl_user_signin` (`create_time` ASC);
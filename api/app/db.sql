use ultrax;

CREATE TABLE pre_rong_reg_code(
 `id` int auto_increment,
 `mobile` varchar(255) default null,
 `regcode` varchar(255) default null,
 `createdon` int default 0,
 PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
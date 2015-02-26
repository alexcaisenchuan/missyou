-- --------------------------------------------------------
-- 用户表
-- --------------------------------------------------------
DROP TABLE IF EXISTS `missyou_users`;
CREATE TABLE `missyou_users` (
	id int NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(id),
	phonenum char(30) NOT NULL,
	name varchar(50) NOT NULL
);

-- --------------------------------------------------------
-- 记录
-- --------------------------------------------------------
DROP TABLE IF EXISTS `missyou_rec`;
CREATE TABLE `missyou_rec` (
	id int NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(id),
	from_id int NOT NULL,
	to_id int NOT NULL,
	add_time datetime,
	latitude double,
	longitude double
);
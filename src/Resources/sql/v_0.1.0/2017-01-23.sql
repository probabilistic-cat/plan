CREATE DATABASE IF NOT EXISTS `todo` CHARACTER SET `utf8mb4` COLLATE `utf8mb4_unicode_ci`;


CREATE TABLE IF NOT EXISTS `user` (
    `user_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_name` VARCHAR(100) NOT NULL,
    `user_password` CHAR(60) NOT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`)
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4` 
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `user_created` BEFORE INSERT ON `user` FOR EACH ROW 
    SET NEW.`created` = NOW();


CREATE TABLE IF NOT EXISTS `task` (
    `task_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `task_parent_id` INT(11) UNSIGNED NULL DEFAULT NULL,
    `task_name` VARCHAR(255) NOT NULL,
    `task_description` TEXT NULL DEFAULT NULL,
    `task_status` ENUM('todo', 'in_process', 'completed') NOT NULL DEFAULT 'todo',
    `task_estimation_time` INT(11) NOT NULL DEFAULT 0,
    `task_due_date` TIMESTAMP NULL DEFAULT NULL,
    `created` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
    `modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`task_id`),
    INDEX `ix__task__user_id`(`user_id` ASC),
    CONSTRAINT 
        FOREIGN KEY fk__task_user__user_id(`user_id`) REFERENCES `user`(`user_id`)
        ON UPDATE CASCADE ON DELETE NO ACTION
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4` 
COLLATE `utf8mb4_unicode_ci`;

CREATE TRIGGER `task_created` BEFORE INSERT ON `task` FOR EACH ROW 
    SET NEW.`created` = NOW();
CREATE TABLE IF NOT EXISTS `task_status` (
    `task_status_code` VARCHAR(32) NOT NULL,
    PRIMARY KEY (`task_status_code`)
)
ENGINE=`InnoDB`
CHARACTER SET `utf8mb4` 
COLLATE `utf8mb4_unicode_ci`;

INSERT INTO `task_status` (`task_status_code`) VALUES ('todo'), ('in_progress'), ('completed');


ALTER TABLE `task`
    ADD COLUMN `task_status_code` VARCHAR(32) NOT NULL DEFAULT 'todo' AFTER `task_description`,
    ADD INDEX `ix__task_task_status__task_status_code`(`task_status_code` ASC),
    ADD CONSTRAINT 
        FOREIGN KEY fk__task_task_status__task_status_code(`task_status_code`) 
        REFERENCES `task_status`(`task_status_code`)
        ON UPDATE CASCADE ON DELETE NO ACTION;


UPDATE `task` 
    SET `task_status_code` = `task_status` 
    WHERE `task_status` = 'todo' OR `task_status` = 'completed';

UPDATE `task`
    SET `task_status_code` = 'in_progress'
    WHERE `task_status` = 'in_process';


ALTER TABLE `task`
    DROP COLUMN `task_status`;

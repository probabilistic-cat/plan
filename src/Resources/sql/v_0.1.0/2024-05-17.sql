ALTER TABLE `task`
    DROP FOREIGN KEY `fk__task_user__user_id`,
    DROP FOREIGN KEY `fk__task_task_status__task_status_code`;

ALTER TABLE `task`
    ADD CONSTRAINT
        FOREIGN KEY fk__task_user__user_id(`user_id`)
            REFERENCES `user`(`user_id`) ON DELETE NO ACTION,
    ADD CONSTRAINT
        FOREIGN KEY fk__task_task_status__task_status_code(`task_status_code`)
            REFERENCES `task_status`(`task_status_code`) ON DELETE NO ACTION;

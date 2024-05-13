ALTER TABLE `user`
    ADD COLUMN `user_active` TINYINT(1) NOT NULL DEFAULT 0 AFTER `user_mail`;

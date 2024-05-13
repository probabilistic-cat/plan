ALTER TABLE `user`
    ADD COLUMN `user_mail` VARCHAR(100) NULL DEFAULT NULL AFTER `user_password`;

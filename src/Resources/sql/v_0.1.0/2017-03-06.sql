ALTER TABLE `user`
    MODIFY COLUMN `user_mail` VARCHAR(100) NOT NULL,
    ADD UNIQUE `iu__user__user_mail`(`user_mail` ASC);

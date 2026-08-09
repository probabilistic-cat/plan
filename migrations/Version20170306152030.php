<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20170306152030 extends AbstractMigration
{
    #[\Override]
    public function getDescription(): string {
        return '';
    }

    #[\Override]
    public function up(Schema $schema): void {
        $this->addSql('
            ALTER TABLE `user`
                MODIFY COLUMN `user_mail` VARCHAR(100) NOT NULL,
                ADD UNIQUE `iu__user__user_mail`(`user_mail` ASC)
        ');
    }

    #[\Override]
    public function down(Schema $schema): void {
        $this->addSql('
            ALTER TABLE `user`
                DROP INDEX `iu__user__user_mail`,
                MODIFY COLUMN `user_mail` VARCHAR(100) NULL DEFAULT NULL AFTER `user_password`
        ');
    }
}

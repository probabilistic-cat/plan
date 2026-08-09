<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20170210152030 extends AbstractMigration
{
    #[\Override]
    public function getDescription(): string {
        return '';
    }

    #[\Override]
    public function up(Schema $schema): void {
        $this->addSql('
            ALTER TABLE `user`
                ADD COLUMN `user_mail` VARCHAR(100) NULL DEFAULT NULL AFTER `user_password`
        ');
    }

    #[\Override]
    public function down(Schema $schema): void {
        $this->addSql('
            ALTER TABLE `user`
                DROP COLUMN `user_mail`;
        ');
    }
}

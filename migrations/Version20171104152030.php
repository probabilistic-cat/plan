<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20171104152030 extends AbstractMigration
{
    #[\Override]
    public function getDescription(): string {
        return '';
    }

    #[\Override]
    public function up(Schema $schema): void {
        $this->addSql('
            ALTER TABLE `user`
                ADD COLUMN `user_active` TINYINT(1) NOT NULL DEFAULT 0 AFTER `user_mail`
        ');
    }

    #[\Override]
    public function down(Schema $schema): void {
        $this->addSql('
            ALTER TABLE `user`
                DROP COLUMN `user_active`
        ');
    }
}

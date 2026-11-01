<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261030172540 extends AbstractMigration
{
    public function up(Schema $schema): void {
        $this->addSql('ALTER TABLE "user" ADD roles JSONB NULL');

        $this->addSql('UPDATE "user" SET roles = \'["ROLE_USER"]\'::jsonb');

        $this->addSql('ALTER TABLE "user" ALTER roles SET NOT NULL');
    }

    public function down(Schema $schema): void {
        $this->addSql('ALTER TABLE "user" DROP roles');
    }
}

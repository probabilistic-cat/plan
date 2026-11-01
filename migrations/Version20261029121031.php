<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261029121031 extends AbstractMigration
{
    public function up(Schema $schema): void {
        $this->addSql('CREATE UNIQUE INDEX iu__user__name ON "user" (name)');
    }

    public function down(Schema $schema): void {
        $this->addSql('DROP INDEX iu__user__name');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261106182905 extends AbstractMigration
{
    public function getDescription(): string {
        return 'Rename Tag code to name.';
    }

    public function up(Schema $schema): void {
        $this->addSql('DROP INDEX iu__tag__code');
        $this->addSql('ALTER TABLE tag RENAME COLUMN code TO name');
        $this->addSql('CREATE UNIQUE INDEX iu__tag__name ON tag (name)');
    }

    public function down(Schema $schema): void {
        $this->addSql('DROP INDEX iu__tag__name');
        $this->addSql('ALTER TABLE tag RENAME COLUMN name TO code');
        $this->addSql('CREATE UNIQUE INDEX iu__tag__code ON tag (code)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261102203106 extends AbstractMigration
{
    public function getDescription(): string {
        return 'Add created_at, updated_at to task_status.';
    }

    public function up(Schema $schema): void {
        $this->addSql('ALTER TABLE task_status ADD created_at BIGINT NULL');
        $this->addSql('UPDATE task_status SET created_at = EXTRACT(EPOCH FROM NOW())');
        $this->addSql('ALTER TABLE task_status ALTER created_at SET NOT NULL');

        $this->addSql('ALTER TABLE task_status ADD updated_at BIGINT DEFAULT NULL');
    }

    public function down(Schema $schema): void {
        $this->addSql('ALTER TABLE task_status DROP created_at');
        $this->addSql('ALTER TABLE task_status DROP updated_at');
    }
}

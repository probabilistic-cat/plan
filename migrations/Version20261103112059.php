<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261103112059 extends AbstractMigration
{
    public function getDescription(): string {
        return 'Create time_log table.';
    }

    public function up(Schema $schema): void {
        $this->addSql('
            CREATE TABLE time_log (
                id UUID NOT NULL,
                user_id UUID NOT NULL, 
                task_id UUID NOT NULL, 
                minutes SMALLINT NOT NULL, 
                created_at BIGINT NOT NULL,
                updated_at BIGINT DEFAULT NULL, 
                PRIMARY KEY (id)
            )
        ');

        $this->addSql('CREATE INDEX ix__time_log__user_id ON time_log (user_id)');
        $this->addSql('CREATE INDEX ix__time_log__task_id ON time_log (task_id)');

        $this->addSql('ALTER TABLE time_log ADD CONSTRAINT fk__time_log__user_id FOREIGN KEY (user_id) REFERENCES "user" (id)');
        $this->addSql('ALTER TABLE time_log ADD CONSTRAINT fk__time_log__task_id FOREIGN KEY (task_id) REFERENCES task (id)');
    }

    public function down(Schema $schema): void {
        $this->addSql('ALTER TABLE time_log DROP CONSTRAINT fk__time_log__user_id');
        $this->addSql('ALTER TABLE time_log DROP CONSTRAINT fk__time_log__task_id');
        $this->addSql('DROP TABLE time_log');
    }
}

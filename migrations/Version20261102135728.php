<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261102135728 extends AbstractMigration
{
    public function getDescription(): string {
        return 'Add priority, assignee_id columns to task table';
    }

    public function up(Schema $schema): void {
        $this->addSql('ALTER TABLE task ADD priority SMALLINT NULL');
        $this->addSql('UPDATE task SET priority = 30');
        $this->addSql('ALTER TABLE task ALTER priority SET NOT NULL');

        $this->addSql('ALTER TABLE task ADD assignee_id UUID DEFAULT NULL');
        $this->addSql('CREATE INDEX ix__task__assignee_id ON task (assignee_id)');

        $this->addSql('
            ALTER TABLE task
                ADD CONSTRAINT fk__task__assignee_user_id
                    FOREIGN KEY (assignee_id)
                    REFERENCES "user" (id)
                    ON DELETE NO ACTION NOT DEFERRABLE
        ');
    }

    public function down(Schema $schema): void {
        $this->addSql('ALTER TABLE task DROP CONSTRAINT fk__task__assignee_user_id');
        $this->addSql('DROP INDEX ix__task__assignee_id');
        $this->addSql('ALTER TABLE task DROP assignee_id');
        $this->addSql('ALTER TABLE task DROP priority');
    }
}

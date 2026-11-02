<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261102115349 extends AbstractMigration
{
    public function getDescription(): string {
        return 'Create story table, add story_id to task table';
    }

    public function up(Schema $schema): void {
        $this->addSql('
            CREATE TABLE story (
                id UUID NOT NULL,
                name TEXT NOT NULL,
                description TEXT DEFAULT NULL,
                epic_id UUID DEFAULT NULL,
                created_at BIGINT NOT NULL,
                updated_at BIGINT DEFAULT NULL,
                PRIMARY KEY (id)
            )
        ');
        $this->addSql('CREATE INDEX ix__story__epic_id ON story (epic_id)');
        $this->addSql('
            ALTER TABLE story
                ADD CONSTRAINT fk__story__epic_id
                    FOREIGN KEY (epic_id) 
                    REFERENCES epic (id) 
                    ON DELETE NO ACTION NOT DEFERRABLE
        ');

        $this->addSql('ALTER TABLE task ADD story_id UUID DEFAULT NULL');
        $this->addSql('CREATE INDEX ix__task__story_id ON task (story_id)');
        $this->addSql('
            ALTER TABLE task
                ADD CONSTRAINT fk__task__story_id
                    FOREIGN KEY (story_id) 
                    REFERENCES story (id) 
                    ON DELETE NO ACTION NOT DEFERRABLE
        ');
    }

    public function down(Schema $schema): void {
        $this->addSql('ALTER TABLE task DROP CONSTRAINT fk__task__story_id');
        $this->addSql('DROP INDEX ix__task__story_id');
        $this->addSql('ALTER TABLE task DROP story_id');
        $this->addSql('ALTER TABLE story DROP CONSTRAINT fk__story__epic_id');
        $this->addSql('DROP INDEX ix__story__epic_id');
        $this->addSql('DROP TABLE story');
    }
}

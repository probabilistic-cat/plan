<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261102153300 extends AbstractMigration
{
    public function getDescription(): string {
        return 'Create tag join tables (task_tag, story_tag, epic_tag)';
    }

    public function up(Schema $schema): void {
        $this->addSql('
            CREATE TABLE task_tag (
                task_id UUID NOT NULL, 
                tag_id UUID NOT NULL, 
                PRIMARY KEY(task_id, tag_id)
            )
        ');
        $this->addSql('
            CREATE TABLE story_tag (
                story_id UUID NOT NULL, 
                tag_id UUID NOT NULL,  
                PRIMARY KEY(story_id, tag_id)
            )
        ');
        $this->addSql('
            CREATE TABLE epic_tag (
                epic_id UUID NOT NULL, 
                tag_id UUID NOT NULL, 
                PRIMARY KEY(epic_id, tag_id)
            )
        ');

        $this->addSql('CREATE INDEX ix__task_tag__tag_id ON task_tag (tag_id)');
        $this->addSql('CREATE INDEX ix__story_tag__tag_id ON story_tag (tag_id)');
        $this->addSql('CREATE INDEX ix__epic_tag__tag_id ON epic_tag (tag_id)');

        $this->addSql('
            ALTER TABLE task_tag 
                ADD CONSTRAINT fk__task_tag__task_id FOREIGN KEY (task_id) REFERENCES task(id)
        ');
        $this->addSql('
            ALTER TABLE task_tag 
                ADD CONSTRAINT fk__task_tag__tag_id FOREIGN KEY (tag_id) REFERENCES tag(id)
        ');

        $this->addSql('
            ALTER TABLE story_tag 
                ADD CONSTRAINT fk__story_tag__story_id FOREIGN KEY (story_id) REFERENCES story(id)
        ');
        $this->addSql('
            ALTER TABLE story_tag 
                ADD CONSTRAINT fk__story_tag__tag_id FOREIGN KEY (tag_id) REFERENCES tag(id)
        ');

        $this->addSql('
            ALTER TABLE epic_tag 
                ADD CONSTRAINT fk__epic_tag__epic_id FOREIGN KEY (epic_id) REFERENCES epic(id)
        ');
        $this->addSql('
            ALTER TABLE epic_tag 
                ADD CONSTRAINT fk__epic_tag__tag_id FOREIGN KEY (tag_id) REFERENCES tag(id)
        ');
    }

    public function down(Schema $schema): void {
        $this->addSql('DROP TABLE task_tag');
        $this->addSql('DROP TABLE story_tag');
        $this->addSql('DROP TABLE epic_tag');
    }
}

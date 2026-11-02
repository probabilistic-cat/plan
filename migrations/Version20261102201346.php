<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261102201346 extends AbstractMigration
{
    public function getDescription(): string {
        return 'Add creator_id to epic, story. Rename task user_id to creator.id.';
    }

    public function up(Schema $schema): void {
        $this->addSql('ALTER TABLE epic ADD creator_id UUID NOT NULL');
        $this->addSql('CREATE INDEX ix__epic__creator_id ON epic (creator_id)');
        $this->addSql('ALTER TABLE epic ADD CONSTRAINT fk__epic__creator_id FOREIGN KEY (creator_id) REFERENCES "user" (id)');

        $this->addSql('ALTER TABLE story ADD creator_id UUID NOT NULL');
        $this->addSql('CREATE INDEX ix__story__creator_id ON story (creator_id)');
        $this->addSql('ALTER TABLE story ADD CONSTRAINT fk__story__creator_id FOREIGN KEY (creator_id) REFERENCES "user" (id)');

        $this->addSql('ALTER TABLE task DROP CONSTRAINT fk__task__user_id');
        $this->addSql('DROP INDEX ix__task__user_id');
        $this->addSql('ALTER TABLE task RENAME COLUMN user_id TO creator_id');
        $this->addSql('CREATE INDEX ix__task__creator_id ON task (creator_id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT fk__task__creator_id FOREIGN KEY (creator_id) REFERENCES "user" (id)');
    }

    public function down(Schema $schema): void {
        $this->addSql('ALTER TABLE epic DROP CONSTRAINT fk__epic__creator_id');
        $this->addSql('DROP INDEX ix__epic__creator_id');
        $this->addSql('ALTER TABLE epic DROP creator_id');

        $this->addSql('ALTER TABLE story DROP CONSTRAINT fk__story__creator_id');
        $this->addSql('DROP INDEX ix__story__creator_id');
        $this->addSql('ALTER TABLE story DROP creator_id');

        $this->addSql('ALTER TABLE task DROP CONSTRAINT fk__task__creator_id');
        $this->addSql('DROP INDEX ix__task__creator_id');
        $this->addSql('ALTER TABLE task RENAME COLUMN creator_id TO user_id');
        $this->addSql('CREATE INDEX ix__task__user_id ON task (user_id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT fk__task__user_id FOREIGN KEY (user_id) REFERENCES "user" (id)');
    }
}

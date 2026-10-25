<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261025110736 extends AbstractMigration
{
    public function getDescription(): string {
        return '';
    }

    public function up(Schema $schema): void {
        $this->addSql('
            CREATE TABLE task_status (
                id UUID NOT NULL,
                code TEXT NOT NULL, 
                PRIMARY KEY (id)
            )
        ');

        $this->addSql('
            INSERT INTO task_status (id, code)
            VALUES (uuidv7(), \'todo\'), (uuidv7(), \'in_progress\'), (uuidv7(), \'completed\') 
        ');

        $this->addSql('
            CREATE TABLE "user" (
                id UUID NOT NULL,
                mail TEXT NOT NULL, 
                name TEXT NOT NULL, 
                password CHAR(60) NOT NULL, 
                active BOOLEAN DEFAULT false NOT NULL, 
                created_at BIGINT NOT NULL, 
                updated_at BIGINT DEFAULT NULL, 
                PRIMARY KEY (id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX iu__user__mail ON "user" (mail)');

        $this->addSql('
            CREATE TABLE task (
                id UUID NOT NULL,
                user_id UUID NOT NULL, 
                task_status_id UUID NOT NULL,
                name TEXT NOT NULL, 
                description TEXT DEFAULT NULL, 
                est_minutes SMALLINT DEFAULT NULL, 
                due_date BIGINT DEFAULT NULL, 
                created_at BIGINT NOT NULL, 
                updated_at BIGINT DEFAULT NULL, 
                PRIMARY KEY (id)
            )
        ');
        $this->addSql('CREATE INDEX ix__task__user_id ON task (user_id)');
        $this->addSql('CREATE INDEX ix__task__task_status_id ON task (task_status_id)');

        $this->addSql('
            ALTER TABLE task 
                ADD CONSTRAINT fk__task__user_id 
                    FOREIGN KEY (user_id) 
                    REFERENCES "user" (id) 
                    ON DELETE NO ACTION NOT DEFERRABLE
        ');
        $this->addSql('
            ALTER TABLE task 
                ADD CONSTRAINT fk__task__task_status_id 
                    FOREIGN KEY (task_status_id) 
                    REFERENCES task_status (id) 
                    ON DELETE NO ACTION NOT DEFERRABLE
        ');
    }

    public function down(Schema $schema): void {
        $this->addSql('ALTER TABLE task DROP CONSTRAINT fk__task__task_status_id');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT fk__task__user_id');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE task_status');
    }
}

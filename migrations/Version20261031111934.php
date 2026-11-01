<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261031111934 extends AbstractMigration
{
    public function up(Schema $schema): void {
        $this->addSql('
            CREATE TABLE epic (
                id UUID NOT NULL,
                name TEXT NOT NULL,
                description TEXT DEFAULT NULL,
                created_at BIGINT NOT NULL,
                updated_at BIGINT DEFAULT NULL,
                PRIMARY KEY (id)
            )
        ');
    }

    public function down(Schema $schema): void {
        $this->addSql('DROP TABLE epic');
    }
}

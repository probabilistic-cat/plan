<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261102110328 extends AbstractMigration
{
    public function up(Schema $schema): void {
        $this->addSql('
            CREATE TABLE tag (
                id UUID NOT NULL,
                code TEXT NOT NULL,
                created_at BIGINT NOT NULL,
                updated_at BIGINT DEFAULT NULL,
                PRIMARY KEY (id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX iu__tag__code ON tag (code)');
    }

    public function down(Schema $schema): void {
        $this->addSql('DROP TABLE tag');
    }
}

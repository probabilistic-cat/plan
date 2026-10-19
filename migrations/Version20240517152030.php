<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240517152030 extends AbstractMigration
{
    #[\Override]
    public function getDescription(): string {
        return '';
    }

    #[\Override]
    public function up(Schema $schema): void {
        $this->addSql('
            ALTER TABLE `task`
                DROP FOREIGN KEY `fk__task_user__user_id`,
                DROP FOREIGN KEY `fk__task_task_status__task_status_code`
        ');

        $this->addSql('
            ALTER TABLE `task`
                ADD CONSTRAINT `fk__task_user__user_id`
                    FOREIGN KEY (`user_id`)
                    REFERENCES `user`(`user_id`)
                    ON DELETE NO ACTION,
                ADD CONSTRAINT `fk__task_task_status__task_status_code`
                    FOREIGN KEY (`task_status_code`)
                    REFERENCES `task_status`(`task_status_code`)
                    ON DELETE NO ACTION
        ');
    }

    #[\Override]
    public function down(Schema $schema): void {
        $this->addSql('
            ALTER TABLE `task`
                DROP FOREIGN KEY `fk__task_user__user_id`,
                DROP FOREIGN KEY `fk__task_task_status__task_status_code`
        ');

        $this->addSql('
            ALTER TABLE `task`
                ADD CONSTRAINT `fk__task_user__user_id`
                    FOREIGN KEY (`user_id`)
                    REFERENCES `user`(`user_id`)
                    ON UPDATE CASCADE ON DELETE NO ACTION,
                ADD CONSTRAINT `fk__task_task_status__task_status_code`
                    FOREIGN KEY (`task_status_code`)
                    REFERENCES `task_status`(`task_status_code`)
                    ON UPDATE CASCADE ON DELETE NO ACTION
        ');
    }
}

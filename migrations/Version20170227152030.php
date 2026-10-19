<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20170227152030 extends AbstractMigration
{
    #[\Override]
    public function getDescription(): string {
        return '';
    }

    #[\Override]
    public function up(Schema $schema): void {
        $this->addSql('
            CREATE TABLE IF NOT EXISTS `task_status` (
                `task_status_code` VARCHAR(32) NOT NULL,
                PRIMARY KEY (`task_status_code`)
            )
            ENGINE=`InnoDB`
            CHARACTER SET `utf8mb4` 
            COLLATE `utf8mb4_unicode_ci`
        ');

        $this->addSql('INSERT INTO `task_status` (`task_status_code`) VALUES ("todo"), ("in_progress"), ("completed")');

        $this->addSql('
            ALTER TABLE `task`
                ADD COLUMN `task_status_code` VARCHAR(32) NOT NULL DEFAULT "todo" AFTER `task_description`,
                ADD INDEX `ix__task_task_status__task_status_code`(`task_status_code` ASC),
                ADD CONSTRAINT `fk__task_task_status__task_status_code`
                    FOREIGN KEY (`task_status_code`)
                    REFERENCES `task_status`(`task_status_code`)
                    ON UPDATE CASCADE ON DELETE NO ACTION
        ');

        $this->addSql('
            UPDATE `task` 
                SET `task_status_code` = `task_status` 
                WHERE `task_status` = "todo" OR `task_status` = "completed"
        ');

        $this->addSql('
            UPDATE `task`
                SET `task_status_code` = "in_progress"
                WHERE `task_status` = "in_process"
        ');

        $this->addSql('
            ALTER TABLE `task`
                DROP COLUMN `task_status`;
        ');
    }

    #[\Override]
    public function down(Schema $schema): void {
        $this->addSql('
            ALTER TABLE `task`
                ADD COLUMN `task_status` ENUM("todo", "in_process", "completed") NOT NULL DEFAULT "todo"
        ');

        $this->addSql('
            UPDATE `task` 
                SET `task_status` = `task_status_code` 
                WHERE `task_status` = "todo" OR `task_status` = "completed"
        ');

        $this->addSql('
            UPDATE `task`
                SET `task_status` = "in_process"
                WHERE `task_status_code` = "in_progress"
        ');

        $this->addSql('
            ALTER TABLE `task`
                DROP FOREIGN KEY `fk__task_task_status__task_status_code`,
                DROP INDEX `ix__task_task_status__task_status_code`,
                DROP COLUMN `task_status_code`
        ');

        $this->addSql('DROP TABLE IF EXISTS `task_status`');
    }
}

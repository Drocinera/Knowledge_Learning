<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241127133122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comprise ADD id INT AUTO_INCREMENT NOT NULL, CHANGE course_id course_id INT DEFAULT NULL, CHANGE purchase_id purchase_id INT DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comprise MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON comprise');
        $this->addSql('ALTER TABLE comprise DROP id, CHANGE course_id course_id INT NOT NULL, CHANGE purchase_id purchase_id INT NOT NULL');
        $this->addSql('ALTER TABLE comprise ADD PRIMARY KEY (course_id, purchase_id)');
    }
}

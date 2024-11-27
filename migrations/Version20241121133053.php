<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241121133053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comprise ADD lesson_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comprise ADD CONSTRAINT FK_B4FA9F2CCDF80196 FOREIGN KEY (lesson_id) REFERENCES lessons (id)');
        $this->addSql('CREATE INDEX IDX_B4FA9F2CCDF80196 ON comprise (lesson_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comprise DROP FOREIGN KEY FK_B4FA9F2CCDF80196');
        $this->addSql('DROP INDEX IDX_B4FA9F2CCDF80196 ON comprise');
        $this->addSql('ALTER TABLE comprise DROP lesson_id');
    }
}

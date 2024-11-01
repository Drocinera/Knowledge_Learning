<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241031144944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE certifications (id INT AUTO_INCREMENT NOT NULL, date_awarded DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comprise (course_id INT NOT NULL, purchase_id INT NOT NULL, price NUMERIC(5, 2) NOT NULL, access_granted TINYINT(1) NOT NULL, INDEX IDX_B4FA9F2C591CC992 (course_id), INDEX IDX_B4FA9F2C558FBEB9 (purchase_id), PRIMARY KEY(course_id, purchase_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE courses (id INT AUTO_INCREMENT NOT NULL, theme_id INT NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(5, 2) NOT NULL, description VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(255) NOT NULL, updated_by VARCHAR(255) NOT NULL, INDEX IDX_A9A55A4C59027487 (theme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gets (user_id INT NOT NULL, certification_id INT NOT NULL, INDEX IDX_4BF68519A76ED395 (user_id), INDEX IDX_4BF68519CB47068A (certification_id), PRIMARY KEY(user_id, certification_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lessons (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, video_url VARCHAR(255) NOT NULL, price NUMERIC(5, 2) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(255) NOT NULL, updated_by VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchases (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, purchase_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_AA6431FEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE themes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(255) NOT NULL, updated_by VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, role_id INT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(255) NOT NULL, updated_by VARCHAR(255) NOT NULL, INDEX IDX_1483A5E9D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE valid (user_id INT NOT NULL, lesson_id INT NOT NULL, date_validated DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8C0735FFA76ED395 (user_id), INDEX IDX_8C0735FFCDF80196 (lesson_id), PRIMARY KEY(user_id, lesson_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comprise ADD CONSTRAINT FK_B4FA9F2C591CC992 FOREIGN KEY (course_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE comprise ADD CONSTRAINT FK_B4FA9F2C558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchases (id)');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4C59027487 FOREIGN KEY (theme_id) REFERENCES themes (id)');
        $this->addSql('ALTER TABLE gets ADD CONSTRAINT FK_4BF68519A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE gets ADD CONSTRAINT FK_4BF68519CB47068A FOREIGN KEY (certification_id) REFERENCES certifications (id)');
        $this->addSql('ALTER TABLE purchases ADD CONSTRAINT FK_AA6431FEA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE valid ADD CONSTRAINT FK_8C0735FFA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE valid ADD CONSTRAINT FK_8C0735FFCDF80196 FOREIGN KEY (lesson_id) REFERENCES lessons (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comprise DROP FOREIGN KEY FK_B4FA9F2C591CC992');
        $this->addSql('ALTER TABLE comprise DROP FOREIGN KEY FK_B4FA9F2C558FBEB9');
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4C59027487');
        $this->addSql('ALTER TABLE gets DROP FOREIGN KEY FK_4BF68519A76ED395');
        $this->addSql('ALTER TABLE gets DROP FOREIGN KEY FK_4BF68519CB47068A');
        $this->addSql('ALTER TABLE purchases DROP FOREIGN KEY FK_AA6431FEA76ED395');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9D60322AC');
        $this->addSql('ALTER TABLE valid DROP FOREIGN KEY FK_8C0735FFA76ED395');
        $this->addSql('ALTER TABLE valid DROP FOREIGN KEY FK_8C0735FFCDF80196');
        $this->addSql('DROP TABLE certifications');
        $this->addSql('DROP TABLE comprise');
        $this->addSql('DROP TABLE courses');
        $this->addSql('DROP TABLE gets');
        $this->addSql('DROP TABLE lessons');
        $this->addSql('DROP TABLE purchases');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE themes');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE valid');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

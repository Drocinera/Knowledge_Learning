<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260407124610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
{
    // USERS
    $this->addSql('CREATE TABLE users (
        id SERIAL NOT NULL,
        role_id INT NOT NULL,
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        is_active BOOLEAN DEFAULT FALSE NOT NULL,
        created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
        updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL,
        created_by VARCHAR(255) NOT NULL,
        updated_by VARCHAR(255) NOT NULL,
        PRIMARY KEY(id)
    )');
    $this->addSql('CREATE UNIQUE INDEX UNIQ_USERS_EMAIL ON users (email)');

    // ROLE
    $this->addSql('CREATE TABLE role (
        id SERIAL NOT NULL,
        name VARCHAR(255) NOT NULL,
        description VARCHAR(255) NOT NULL,
        PRIMARY KEY(id)
    )');

    // THEMES
    $this->addSql('CREATE TABLE themes (
        id SERIAL NOT NULL,
        name VARCHAR(255) NOT NULL,
        description VARCHAR(255) NOT NULL,
        image VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
        created_by VARCHAR(255) NOT NULL,
        updated_by VARCHAR(255) NOT NULL,
        PRIMARY KEY(id)
    )');

    // COURSES
    $this->addSql('CREATE TABLE courses (
        id SERIAL NOT NULL,
        theme_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        price NUMERIC(5,2) NOT NULL,
        description VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
        created_by VARCHAR(255) NOT NULL,
        updated_by VARCHAR(255) NOT NULL,
        PRIMARY KEY(id)
    )');

    // LESSONS
    $this->addSql('CREATE TABLE lessons (
        id SERIAL NOT NULL,
        course_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        video_url VARCHAR(255) DEFAULT NULL,
        price NUMERIC(5,2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
        created_by VARCHAR(255) NOT NULL,
        updated_by VARCHAR(255) NOT NULL,
        PRIMARY KEY(id)
    )');

    // PURCHASES
    $this->addSql('CREATE TABLE purchases (
        id SERIAL NOT NULL,
        user_id INT NOT NULL,
        purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY(id)
    )');

    // CERTIFICATIONS
    $this->addSql('CREATE TABLE certifications (
        id SERIAL NOT NULL,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT DEFAULT NULL,
        date_awarded TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY(id)
    )');

    // COMPRISE
    $this->addSql('CREATE TABLE comprise (
        id SERIAL NOT NULL,
        course_id INT DEFAULT NULL,
        purchase_id INT DEFAULT NULL,
        lesson_id INT DEFAULT NULL,
        price NUMERIC(5,2) NOT NULL,
        access_granted BOOLEAN NOT NULL,
        PRIMARY KEY(id)
    )');

    // GETS (clé composite)
    $this->addSql('CREATE TABLE gets (
        user_id INT NOT NULL,
        certification_id INT NOT NULL,
        PRIMARY KEY(user_id, certification_id)
    )');

    // VALID (clé composite)
    $this->addSql('CREATE TABLE valid (
        user_id INT NOT NULL,
        lesson_id INT NOT NULL,
        date_validated TIMESTAMP DEFAULT NULL,
        PRIMARY KEY(user_id, lesson_id)
    )');

    // FK
    $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_USERS_ROLE FOREIGN KEY (role_id) REFERENCES role (id)');
    $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_COURSES_THEME FOREIGN KEY (theme_id) REFERENCES themes (id)');
    $this->addSql('ALTER TABLE lessons ADD CONSTRAINT FK_LESSONS_COURSE FOREIGN KEY (course_id) REFERENCES courses (id)');
    $this->addSql('ALTER TABLE purchases ADD CONSTRAINT FK_PURCHASES_USER FOREIGN KEY (user_id) REFERENCES users (id)');
    $this->addSql('ALTER TABLE certifications ADD CONSTRAINT FK_CERT_USER FOREIGN KEY (user_id) REFERENCES users (id)');
    $this->addSql('ALTER TABLE comprise ADD CONSTRAINT FK_COMPRISE_COURSE FOREIGN KEY (course_id) REFERENCES courses (id)');
    $this->addSql('ALTER TABLE comprise ADD CONSTRAINT FK_COMPRISE_PURCHASE FOREIGN KEY (purchase_id) REFERENCES purchases (id)');
    $this->addSql('ALTER TABLE comprise ADD CONSTRAINT FK_COMPRISE_LESSON FOREIGN KEY (lesson_id) REFERENCES lessons (id)');
    $this->addSql('ALTER TABLE gets ADD CONSTRAINT FK_GETS_USER FOREIGN KEY (user_id) REFERENCES users (id)');
    $this->addSql('ALTER TABLE gets ADD CONSTRAINT FK_GETS_CERT FOREIGN KEY (certification_id) REFERENCES certifications (id)');
    $this->addSql('ALTER TABLE valid ADD CONSTRAINT FK_VALID_USER FOREIGN KEY (user_id) REFERENCES users (id)');
    $this->addSql('ALTER TABLE valid ADD CONSTRAINT FK_VALID_LESSON FOREIGN KEY (lesson_id) REFERENCES lessons (id)');
}
}
